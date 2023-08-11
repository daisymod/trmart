<?php

namespace App\Services;

use App\Exports\ImportDataCatalogResultExport;
use App\Mail\ResultImportMail;
use App\Models\CatalogCharacteristicItem;
use App\Models\CatalogItem;
use App\Models\CatalogItemDynamicCharacteristic;
use App\Models\Compound;
use App\Models\ParseStatistic;
use App\Models\ItemCompoundTable;
use App\Models\MarketplaceBrands;
use App\Models\ProductItem;
use App\Models\User;
use App\Requests\ImageRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CatalogItemsExcelLoadService
{

    public function __construct(protected CompoundModelService $compound,protected ItemService $item,protected ProductItemSerice $product,protected CharacteristicService $characteristic)
    {
    }


    public static function saveParseImage($image){

        try {
            if (empty($image)){
                return json_encode([
                    "file" => '',
                    "name" => '',
                    'img' => '',
                    'small' => '',
                ]);
            }

            $file = @file_get_contents($image);

            if($file === FALSE) {
                return json_encode([
                    "file" => '',
                    "name" => '',
                    'img' => '',
                    'small' => '',
                ]);
            }


            $md5 = md5($file);
            $ext ='png';
            $fileName = "/$md5.$ext";

            $path = public_path($fileName);

            file_put_contents($path, $file);

            $info = [
                "file" => $fileName,
                "name" => $md5,
                'img' => $fileName,
                'small' => $fileName,
            ];
        }catch (\Exception $e){
            return json_encode([
                "file" => '',
                "name" => '',
                'img' => '',
                'small' => '',
            ]);
        }




        return json_encode($info);
    }


    public static function load($excelArray,$user,$jobId = null)
    {
        $excelArray = self::getArrayFromFile($excelArray);
        unset($excelArray[0]);
        $newData = [
            'job_id' =>   $jobId == null ? 1 : $jobId,
            'user_id' => $user->id,
            'start_parse' => Carbon::now(),
            'end_parse' => null,
            'file' => null,
            'count_of_lines' => count($excelArray),
            'status' => 'in progress',
        ];
        $characteristicData = array();
        $resultArrayParse = $excelArray;
        $i = 1;
        $resultSuccess = 0;
        $resultError = 0;
        $array_update = array();
        $parseStatistic = new ParseStatistic();
        $parseStatisticService = new ParseStatisticService($parseStatistic);

        $fileResultName = Carbon::now().'-load-data.xlsx';
        $parseStat = $parseStatisticService->create($newData);
        $requestImage = new ImageRequest();
        try {

            foreach ($excelArray as $number => $row) {
                if ($row[0] == null && $row[1] == null  && $row[2] == null ){
                    break;
                }

                $getLastRowToResult = 28;

                if (empty($row[7])){
                    $resultArrayParse[$i][$getLastRowToResult + 1] = 'IMG value cannot be empty';
                    $i++;
                    $resultError++;
                    continue;
                }

                $images = explode(',', $row[7]);
                $galleryResult = null;
                $gallery = [$row[22] ?? null,$row[23] ?? null,$row[24] ?? null,$row[25] ?? null,$row[26]?? null,$row[27] ?? null];


                $catalog = str_replace('"]', '', str_replace('["', '', $row[16]));
                $merchant = str_replace('"]', '', str_replace('["', '', $row[17]));
                $getLastId = CatalogItem::orderbyDesc('id')->first();
                $article = substr("0000000000".$getLastId->id + 1, strlen($getLastId->id + 1));


                $checkImage = $requestImage->getData($images[0]);
                if ($checkImage['code'] == 200)
                {
                    $imageResult = self::saveParseImage(str_replace('[{"file":','',$checkImage['data']));
                }
                else
                {
                    $imageResult = '{"file":"\/img\/no_img.jpeg",
                                "name":"\/img\/no_img.jpeg",
                                "img":"\/img\/no_img.jpeg",
                                "small":"\/img\/no_img.jpeg"}';
                }


                if ($user->role == 'admin'){
                    $loadUser = empty($merchant) ? 0 : $merchant;
                }else{
                    $loadUser = $user->id;
                }




                if (empty($row[11])){
                    $resultArrayParse[$i][$getLastRowToResult + 1] = 'Color value cannot be empty';
                    $i++;
                    $resultError++;
                    continue;
                }


                if (empty($row[12])){
                    $resultArrayParse[$i][$getLastRowToResult + 1] = 'Size value cannot be empty';
                    $i++;
                    $resultError++;
                    continue;
                }


                $dataItem = [
                    'name' => [
                        'ru' => $row[0],
                        'tr' => $row[1],
                        'kz' => $row[2],
                    ],
                    'brand' => $row[18],
                    'article' => $article,
                    'barcode' => $row[8],
                    'body' => [
                        'ru' => $row[4],
                        'kz' => $row[6],
                        'tr' => $row[5],
                    ],
                    'active' => $row[15],
                    'status' => $row[14],
                    'status_text' => $row[14],

                    'sale' => $row[9],
                    'price' => $row[10] ?? 0,
                    'count' => $row[13],
                    'catalog' => [
                        empty($catalog) ? 0 : $catalog,
                    ],
                    'user' => [
                        $loadUser,
                    ],
                    'image' => $imageResult,
                    'weight' => $row[21] ?? 1,
                ];

                $model = new CatalogItem();
                $item = new ItemService($model);

                $checkCatalog = $item->checkExist($dataItem);

                $modelCharacteristic =  new CatalogItemDynamicCharacteristic;
                $characteristic = new CharacteristicService($modelCharacteristic);
                $productItemModel = new ProductItem();
                $productItemService = new ProductItemSerice($productItemModel);
                $compoundModel = new Compound();
                $compound = new CompoundModelService($compoundModel);

                if (!empty($checkCatalog->id)) {
                    $is_create = false;

                    if (!in_array($checkCatalog->id,$array_update)){
                        $catalog_id = $checkCatalog;
                        $item->update($dataItem,$catalog_id->id,$user);

                        $productItemService->delete($catalog_id->id);
                        array_push($array_update,$checkCatalog->id);
                    }

                    $characteristic->delete($catalog_id->id);

                } else {
                    $is_create = true;
                    $catalog_id = $item->create($dataItem,$user);
                    array_push($array_update,$catalog_id->id);
                }



                ItemCompoundTable::where('item_id','=',$catalog_id->id)
                    ->delete();



                if (empty(json_decode($imageResult)->file)){
                    $resultArrayParse[$i][$getLastRowToResult + 1] = 'File image not found, take another url for image';
                    $resultError++;
                }else{
                    $resultSuccess++;
                }

                $marketBrandModel = new MarketplaceBrands();
                $brandService = new MarketPlaceBrandService($marketBrandModel);

                $brandService->create($row[18]);


                $compoundData =  str_replace(']', '', str_replace('[', '', $row[3]));
                if (substr($compoundData, -1) == ','){
                    $compoundData = substr($compoundData, 0, -1);
                }

                $compoundDataRu = str_replace(']', '', str_replace('[', '', $row[19]));
                $compoundDataKz = str_replace(']', '', str_replace('[', '', $row[20]));
                $compoundData = explode(",", $compoundData);
                $compoundDataRu = explode(",", $compoundDataRu);
                $compoundDataKz = explode(",", $compoundDataKz);


                $indexForCreate = 0;


                foreach ($compoundDataRu as $compoundItem) {
                    if ($indexForCreate % 2 == 1 || empty($compoundItem)) {
                        $indexForCreate++;
                        continue;
                    } else {
                        $attribute = [
                            'name_ru' =>  $compoundDataRu[$indexForCreate] ?? '',
                            'name_tr' =>  $compoundData[$indexForCreate] ?? '',
                            'name_kz' =>  $compoundDataKz[$indexForCreate] ?? '',
                        ];

                        $percent = intval($compoundData[$indexForCreate + 1]) ?? '0';
                        $compoundExist = Compound::where('name_tr','=',$attribute['name_tr'])
                            ->first();
                        if (!empty($compoundExist->name_tr)){
                            ItemCompoundTable::create(
                                [
                                    'item_id'       =>    $catalog_id->id,
                                    'compound_id'   =>    $compoundExist->id,
                                    'percent'       =>    $percent
                                ]
                            );
                        }else{
                            $newCompound = $compound->create($attribute);
                            ItemCompoundTable::create(
                                [
                                    'item_id'       =>    $catalog_id->id,
                                    'compound_id'   =>    $newCompound->id,
                                    'percent'       =>    $percent
                                ]
                            );
                        }

                        $indexForCreate++;
                    }
                }


                $color = CatalogCharacteristicItem::where('catalog_characteristic_id', '=', 15)
                    ->where('name_tr', '=', $row[11])
                    ->orWhere('name_kz', '=', $row[11])
                    ->orWhere('name_ru', '=', $row[11])
                    ->first();

                if (!empty($color->id)) {
                    $colorData = $color->id;
                } else {
                    $newColor = CatalogCharacteristicItem::create([
                        'name_ru' => $row[11],
                        'name_tr' => $row[11],
                        'name_kz' => $row[11],
                        'catalog_characteristic_id' => 15,
                        'position' => 1,
                    ]);
                    $colorData = $newColor->id;
                }

                $size = CatalogCharacteristicItem::where('catalog_characteristic_id', '=', 16)
                    ->where('name_tr', '=', $row[12])
                    ->first();

                if (!empty($size->id)) {
                    $sizeData = $size->id;
                } else {
                    $newSize = CatalogCharacteristicItem::create([
                        'name_ru' => $row[12],
                        'name_tr' => $row[12],
                        'name_kz' => $row[12],
                        'catalog_characteristic_id' => 16,
                        'position' => 1,
                    ]);
                    $sizeData = $newSize->id;
                }


                $checkProductItem = ProductItem::where('color','=',$colorData)
                    ->where('size','=',$sizeData)
                    ->where('item_id','=',$catalog_id->id)
                    ->first();



                $checkProductItemColor = ProductItem::where('color','=',$colorData)
                    ->where('item_id','=',$catalog_id->id)
                    ->first();

                if (!empty($checkProductItemColor->size)){
                    $productData = [
                        'color' => $colorData,
                        'size' => $sizeData,
                        'price' => $row[10],
                        'count' => empty($row[13]) ? 0 : $row[13],
                        'sale' => empty($row[9]) ? 0 : $row[9],
                        'image' => substr(substr($checkProductItemColor->image, 0, -1),1),
                    ];
                }else{
                    foreach ($gallery as $item){
                        if (!empty($item)){
                            $galleryImageData = $requestImage->getData($item);
                            if ($galleryImageData['code'] == 200)
                            {
                                $galleryResult .= self::saveParseImage($item).',';
                            }
                        }
                    }
                    $productData = [
                        'color' => $colorData,
                        'size' => $sizeData,
                        'price' => $row[10],
                        'count' => empty($row[13]) ? 0 : $row[13],
                        'sale' => empty($row[9]) ? 0 : $row[9],
                        'image' => substr($galleryResult, 0, -1),
                    ];
                }


                if (!empty($checkProductItem->size)){
                    $productItemService->update($productData, $checkProductItem->id);
                }else{
                    $productItemService->create($productData, $catalog_id->id);
                }

                $i++;
            }
        }catch (\Exception $e){
            print_r($e);
        }

        self::makeCsv($resultArrayParse,$fileResultName);
        $update = [
            'status' => 'done',
            'end_parse' => Carbon::now(),
            'file' => $fileResultName,
            'load_lines' => $i,
        ];
        $parseStatisticService->update($update,$parseStat->id);

        if (!empty($user->email)){
            Mail::to($user->email)->send(new ResultImportMail($user,$resultArrayParse,$user->lang,$resultSuccess,$resultError));
        }
        $adminUser = User::where('id','=',1)
            ->first();
        if (!empty($adminUser->email)){
            Mail::to($adminUser->email)->send(new ResultImportMail($adminUser,$resultArrayParse,$adminUser->lang,$resultSuccess,$resultError));
        }
        return $characteristicData;
    }


    public static function makeCsv($array,$fileResultName){
        $attachment = Excel::raw(
            new ImportDataCatalogResultExport($array, 'ru'),
            \Maatwebsite\Excel\Excel::XLSX
        );

        Storage::disk('public-files')->put($fileResultName, $attachment);
    }

    public static function getArrayFromFile($file): array
    {
        $reader = new Xlsx();
        $path = storage_path('app/public/' . $file);

        $spreadsheet = $reader->load($path);
        return $spreadsheet->getSheet(0)->toArray();
    }

}
