<?php

namespace App\Services;

use App\Actions\ImageLoadAction;
use App\Events\CheckNotExistCharacteristicEvent;
use App\Mail\ResultImportMail;
use App\Models\Catalog;
use App\Models\CatalogCharacteristic;
use App\Models\CatalogCharacteristicItem;
use App\Models\CatalogItem;
use App\Models\CatalogItemDynamicCharacteristic;
use App\Models\Color;
use App\Models\ItemCompound;
use App\Models\MarketplaceBrands;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CatalogItemsExcelLoadService
{

    public function __construct(protected CompoundService $compound,protected ItemService $item,protected ProductItemSerice $product,protected CharacteristicService $characteristic)
    {
    }


    public static function saveParseImage($image){

        try {
            $file = file_get_contents($image);
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




    public static function load($excelArray,$user)
    {
        $header = $excelArray[0];
        unset($excelArray[0]);
        $characteristicData = array();
        $resultArrayParse = $excelArray;
        $i = 1;
        $resultSuccess = 0;
        $resultError = 0;
        $flagDeleteBefore = true;
        $array_update = array();

        foreach ($excelArray as $number => $row) {
            if ($row[0] == null && $row[1] == null  && $row[2] == null ){
                break;
            }
            $images = explode(',', $row[7]);


            $catalog = str_replace('"]', '', str_replace('["', '', $row[16]));
            $merchant = str_replace('"]', '', str_replace('["', '', $row[17]));
            $getLastId = CatalogItem::orderbyDesc('id')->first();
            $article = substr("0000000000".$getLastId->id + 1, strlen($getLastId->id + 1));

            $imageResult = self::saveParseImage(str_replace('[{"file":','',$images[0]));

            if ($user->role == 'admin'){
                $loadUser = empty($merchant) ? 0 : $merchant;
            }else{
                $loadUser = $user->id;
            }


            $data = CatalogCharacteristic::all();

            $indexRowCharacteristic = 22;
            $getLastRowToResult = 22 + count($data);

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
                'country_title' => null,
                'city_id' => null,
                'country_id' => null,
                'equipment' => [
                    'ru' => '',
                    'tr' => '',
                    'kz' => '',
                ],
                'body' => [
                    'ru' => $row[4],
                    'tr' => $row[5],
                    'kz' => $row[6],
                ],
                'active' => $row[15],
                'status' => $row[14],
                'status_text' => $row[14],
                'sex' => 1,
                'style' => null,
                'size' => null,
                'sale' => $row[9],
                'length' => null,
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
            $compoundModel = new ItemCompound();
            $compound = new CompoundService($compoundModel);

            if (!empty($checkCatalog->id)) {
                $is_create = false;

               if (!in_array($checkCatalog->id,$array_update)){
                   $catalog_id = $checkCatalog;
                   $item->update($dataItem,$catalog_id->id,$user);

                   $productItemService->delete($catalog_id->id);
                   array_push($array_update,$checkCatalog->id);
               }
                $characteristic->delete($catalog_id->id);
                $compound->delete($catalog_id->id);
            } else {
                $is_create = true;
                $catalog_id = $item->create($dataItem,$user);
            }




            foreach ($data as $rowData){
                
                $rowArray = str_replace('"]', '', str_replace('["', '', $row[$indexRowCharacteristic] ));

                $rowArray = explode("\",\"", $rowArray);

                $characteristicID = CatalogCharacteristic::where('name_ru','=',$header[$indexRowCharacteristic])
                    ->orWhere('name_tr','=',$header[$indexRowCharacteristic])
                    ->orWhere('name_kz','=',$header[$indexRowCharacteristic])
                    ->first();
                if (!empty($characteristicID->id)){
                    $characteristic_id = $characteristicID->id;
                }else{
                    $characteristic_id = $rowData->id;
                }

                if ((empty($rowArray[0]) && empty($rowArray[1]) && empty($rowArray[2]))){
                    $indexRowCharacteristic++;
                    continue;
                }else{
                    $insert = [
                        'item_id' => $catalog_id->id,
                        'characteristic_id' =>$characteristic_id,
                        'name_ru' => $rowArray[0],
                        'name_tr' => $rowArray[1],
                        'name_kz' => $rowArray[2],
                    ];

                    $characteristic->create($insert);
                    $indexRowCharacteristic++;
                }

            }



            if ($is_create == true){
                $resultArrayParse[$i][$getLastRowToResult] = 'Product create - successful. Article - '.$article;
            }else{
                $resultArrayParse[$i][$getLastRowToResult] = 'Product update - successful. Article - '.$catalog_id->article;
            }


            if (empty(json_decode($imageResult)->file)){
                $resultArrayParse[$i][$getLastRowToResult + 1] = 'File image not found, take another url for image';
                $resultError++;
            }else{
                $resultSuccess++;
            }

            $marketBrandModel = new MarketplaceBrands();
            $brandService = new MarketPlaceBrandService($marketBrandModel);

            $brandService->create($row[18]);


            $compoundData = explode(" ", $row[3]);

            $compoundArray = array();
            $index = 0;

            if (count($compoundData) % 2 == 1) {
                array_push($compoundData, '');
            }

            foreach ($compoundData as $compoundItem) {

                if ($index == 0 || $index % 2 == 0) {
                    $index++;
                    continue;
                } else {
                    array_push($compoundArray, ['percent' => intval(str_replace('%', '', $compoundData[$index])) ?? 100, 'name_ru' => str_replace('p>', '', $compoundData[$index -1]), 'name_tr' => str_replace('p>', '', $compoundData[$index - 1]), 'name_kz' => str_replace('p>', '', $compoundData[$index -1])]);
                }

                $index++;
            }





            foreach ($compoundArray as $item) {
                $compound->create($item, $catalog_id->id);
            }

            $color = CatalogCharacteristicItem::where('catalog_characteristic_id', '=', 15)
                ->where('name_tr', '=', $row[11])
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


            $productData = [
                'color' => $colorData,
                'size' => $sizeData,
                'price' => $row[10],
                'count' => empty($row[13]) ? 0 : $row[13],
                'sale' => empty($row[9]) ? 0 : $row[9],
            ];


            $checkProductItem = ProductItem::where('color','=',$colorData)
                                ->where('size','=',$sizeData)
                                ->where('item_id','=',$catalog_id->id)
                                ->first();
            if (!empty($checkProductItem->id)){
                $productItemService->update($productData, $catalog_id->id);
            }else{
                $productItemService->create($productData, $catalog_id->id);
            }

            $i++;
        }

        //Mail::to($user->email)->send(new ResultImportMail($user,$resultArrayParse,$user->lang,$resultSuccess,$resultError));

        return $characteristicData;
    }

    public static function getArrayFromFile($file): array
    {
        $reader = new Xlsx();
        $spreadsheet = $reader->load($file);
        return $spreadsheet->getSheet(0)->toArray();
    }

}
