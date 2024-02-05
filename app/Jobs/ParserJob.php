<?php

namespace App\Jobs;

use App\Exports\ImportDataCatalogResultExport;
use App\Exports\ParseExport;
use App\Mail\ParserMail;
use App\Models\CatalogCharacteristic;
use App\Models\ParseImport;
use App\Models\User;
use App\Requests\MSRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ParserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected  $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        if (!str_contains($this->request['url'], "www.ozdilekteyim.com")) {
            throw \Illuminate\Validation\ValidationException::withMessages(['Site Is wrong']);
        }
        $log = ParseImport::create(
            [
                'job_id' => $this->job->getJobId(),
                'domain' => 'https://www.ozdilekteyim.com',
                'status' => 'in progress',
                "error" => 'none',
                'uuid' => $this->job->uuid(),
                'url' => $this->request['url'],
            ]
        );
        $start = Carbon::now();
        $link_array = explode('/',$this->request['url']);
        $category = end($link_array);
        $service = new MSRequest();
        $response = $service->getData($category,0);

        $totalPages = $response['data']['pagination']['totalPages'] ?? 1;
        $productExcel = array();


        for ($page = 0;$page < $totalPages; $page++){
            $pageResponse = $service->getData($category,$page);
            foreach ($pageResponse['data']['products'] as $product){

                $dataProduct = $service->getDataProduct($product['customUrl']);
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

                $check_width = explode(' ',$descriptionProduct);
                $length_data = 1;
                $width_data = 1;
                foreach ($check_width as $word){
                    if (preg_match('/^\d+x\d+/', $word)){
                        $length = explode('x',$word);
                        $length_data = intval($length[0]) ?? 1;
                        $width_data = intval($length[1]) ?? 1;
                    }
                }

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
                                $this->request['status'] ?? 1,
                                $this->request['active'] ?? 'N',
                                $this->request['catalog'] ?? 0,
                                $this->request['user'] ?? 0,
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
                                $length_data,
                                $width_data,
                                1,
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
                                    $this->request['status'] ?? 1,
                                    $this->request['active'] ?? 'N',
                                    $this->request['catalog'] ?? 0,
                                    $this->request['user'] ?? 0,
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
                                    $length_data,
                                    $width_data,
                                    1,
                                ]));
                        }
                    }
                }

            }

        }

        $adminUser = User::where('id','=',1)
            ->first();
        if (!empty($adminUser->email)){
            Mail::to($adminUser->email)->send(new ParserMail($adminUser,$productExcel,$user->lang ?? 'tr',$this->request['url']));
        }

        if (!empty($this->request['user'])){
            $user = User::where('id','=',$this->request['user'] )
                ->first();
            Mail::to($user->email)->send(new ParserMail($user,$productExcel,$user->lang ?? 'tr',$this->request['url']));
        }

        $end = Carbon::now();
        $minuteDiff = $end->diffInSeconds($start);

        $attachment = Excel::raw(
            new ImportDataCatalogResultExport($productExcel, 'ru'),
            \Maatwebsite\Excel\Excel::XLSX
        );
        $fileResultName = Carbon::now().'-import-data.xlsx';
        Storage::disk('public-files')->put($fileResultName, $attachment);

        $log->time = $minuteDiff;
        $log->totalCount = count($productExcel);
        $log->status = 'done';
        $log->file = $fileResultName;
        $log->save();

        if (ob_get_length() == 0 ) {
            ob_start();
            $result = Excel::download(new ParseExport($productExcel,'tr'), 'Parse.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            ob_end_clean();

        }
    }
}
