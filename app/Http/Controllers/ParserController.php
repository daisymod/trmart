<?php

namespace App\Http\Controllers;

use App\Exports\ParseExport;

use App\Forms\AdminParserForm;
use App\Forms\BrandEditForm;;

use App\Forms\CatalogCharacteristicItemAdminForm;
use App\Forms\MerchantParserForm;
use App\Forms\ParserAdminForm;
use App\Models\Brand;
use App\Models\CatalogCharacteristic;
use App\Models\CatalogItem;
use App\Requests\MSRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Nette\Schema\ValidationException;


class ParserController extends Controller
{

    public function __construct(protected MSRequest $service)
    {
    }

    public function index(){
        Gate::authorize("parser-list");

        if (Auth::user()->role == 'merchant'){
            $form  = new MerchantParserForm(new CatalogItem());
        }
        else{
            $form  = new AdminParserForm(new CatalogItem());
        }

        $form = $form->formRenderEdit();
        return view("parser.index",['form' => $form]);
    }


    public function actionExcelExport(Request $request){
        ini_set('max_execution_time', 6000);

        set_time_limit(6000);
        if (!str_contains(request()->url, "www.ozdilekteyim.com")) {
            throw \Illuminate\Validation\ValidationException::withMessages(['Site Is wrong']);
        }

        $link_array = explode('/',request()->url);
        $category = end($link_array);
        $response = $this->service->getData($category,0);

        $totalPages = $response['data']['pagination']['totalPages'] ?? 1;
        $productExcel = array();


        for ($page = 0;$page < $totalPages; $page++){
            $pageResponse = $this->service->getData($category,$page);

            foreach ($pageResponse['data']['products'] as $product){
                $dataProduct = $this->service->getDataProduct($product['customUrl']);
                    $images = array();
                    $gallery = array();
                    foreach ($dataProduct['data']['images'] as $image){
                        if (str_contains($image['url'], '1200/1200')) {
                            array_push($gallery, $image['url']);
                        }
                        array_push($images, $image['url']);
                    }

                    $html = strip_tags($dataProduct['data']['description'],'<p>');
                    $description  = explode('<',$html);


                    $textTag = str_replace('<',' ',$dataProduct['data']['description']);
                    $text = explode(' ',$textTag);
                    $compound_text = '';
                    $index = 0;



                    foreach ($text as $item){
                        if (str_contains($item,'%')){
                            $compound_text .= '"'. $text[$index+1] .'","'.str_replace('%','',$text[$index]).'",';
                            $index++;
                        }else{
                            $index++;
                        }
                    }

                    $descriptionProduct =  str_replace('p>Tanımlama:', '', $description[3] ?? $description);


                    $data = CatalogCharacteristic::all();
                    $characteristicArray = array();
                    foreach ($data as $rowData){
                        array_push($characteristicArray,'["","",""]');
                    }

                    if ($compound_text == ''){
                        $compound_text = "['',0]";
                    }else{
                        $compound_text = "[$compound_text]";
                    }

                    if (isset($dataProduct['data']['variantOptions'])){
                        foreach ($dataProduct['data']['variantOptions'] as $values){
                            array_push($productExcel,
                                array_merge([
                                    $dataProduct['data']['name'],
                                    $dataProduct['data']['name'],
                                    $dataProduct['data']['name'],
                                    str_replace('"','',$compound_text),
                                    $descriptionProduct,
                                    $descriptionProduct,
                                    $descriptionProduct,
                                    implode(',',$images),
                                    $values['code'],
                                    $values['discountRate'] ?? 0,
                                    $values['listPrice']['value'] ?? $values['priceData']['value'],
                                    $values['variantOptionQualifiers'][0]['value'],
                                    $values['variantOptionQualifiers'][1]['value'],
                                    $values['stock']['stockLevel'] ?? $product['variantCount'],
                                    request()->status ?? 1,
                                    request()->active ?? 'N',
                                    request()->catalog ?? 0,
                                    request()->user ?? 0,
                                    $product['brand'],
                                    ["",0],
                                    ["",0],
                                    1,
                                    $gallery[0] ?? '',
                                    $gallery[1] ?? '',
                                    $gallery[2] ?? '',
                                    $gallery[3] ?? '',
                                    $gallery[4] ?? '',
                                    $gallery[5] ?? '',
                                ])
                            );
                        }
                    }else{

                        foreach ($dataProduct['data']['baseOptions'] as $values) {
                            foreach ($values['options'] as $item) {

                                array_push($productExcel,
                                    array_merge([
                                        $dataProduct['data']['name'],
                                        $dataProduct['data']['name'],
                                        $dataProduct['data']['name'],
                                        str_replace('"','',$compound_text),
                                        $descriptionProduct,
                                        $descriptionProduct,
                                        $descriptionProduct,
                                        implode(',',$images),
                                        $item['code'],
                                        $item['discountRate'] ?? 0,
                                        $item['listPrice']['value'] ?? $item['priceData']['value'],
                                        $item['variantOptionQualifiers'][0]['value'],
                                        $item['variantOptionQualifiers'][1]['value'] ?? 'standart',
                                        $item['stock']['stockLevel'] ?? $product['variantCount'],
                                        request()->status ?? 1,
                                        request()->active ?? 'N',
                                        request()->catalog ?? 0,
                                        request()->user ?? 0,
                                        $product['brand'],
                                        ["",0],
                                        ["",0],
                                        1,
                                        $gallery[0] ?? '',
                                        $gallery[1] ?? '',
                                        $gallery[2] ?? '',
                                        $gallery[3] ?? '',
                                        $gallery[4] ?? '',
                                        $gallery[5] ?? '',
                                    ]));
                            }
                        }
                    }

               }


        }

        if (ob_get_length() == 0 ) {
            ob_start();
            $result = Excel::download(new ParseExport($productExcel,$request->get('locale') ?? 'tr'), 'Parse.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            ob_end_clean();

            return $result;
        }

    }

}
