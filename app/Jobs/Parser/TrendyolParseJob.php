<?php

namespace App\Jobs\Parser;

use App\Exports\ImportDataCatalogResultExport;
use App\Exports\ParseExport;
use App\Mail\ParserMail;
use App\Models\ParseImport;
use App\Models\User;
use App\Requests\Trendyol\TrendyolParser;
use App\Requests\TrendyolRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TrendyolParseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 9999999;
    public $tries = 1;
    public $backoff = [2, 10, 20];

    public $import;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $log = ParseImport::create(
        [
            'job_id' => $this->job->getJobId(),
            'domain' => 'https://www.trendyol.com',
            'status' => 'in progress',
            "error" => 'none',
            'uuid' => $this->job->uuid(),
            'url' => $this->import['url'],
            'catalog' => $this->import['catalog'][0] ?? null,
            'merchant' => $this->import['user'][0] ?? null,
       ]
        );
        $start = Carbon::now();
        $pars = new TrendyolParser();

        $send = new TrendyolRequest();

        $page = $pars->getPageResponse($this->import['url']);

        $found = preg_match('/window\.__SEARCH_APP_INITIAL_STATE__=(.+);/', $page);

        $productExcel = array();


        if ($found) {
            $text = substr($page, strpos($page, '__SEARCH_APP_INITIAL_STATE__') + 29);
            $to = strpos($text, '};');
            $json = substr($text, 0, $to);
            $data = json_decode($json."}",true);
        }

        $totalPage = 1;
        $page = 1;
        if (isset($data['totalCount'])){
            $totalPage = ceil($data['totalCount'] / 24);
        }

        while ($totalPage >= $page){
            $url = $this->import['url'];
            $url = $url."?pi=".$page;
            Log::info(print_r($page,true));
            $pageCategory = $send->getData($url);
            if (gettype($pageCategory) == 'string'){
                $found = preg_match('/window\.__SEARCH_APP_INITIAL_STATE__=(.+);/', $pageCategory);


                if ($found) {
                    $text = substr($pageCategory, strpos($pageCategory, '__SEARCH_APP_INITIAL_STATE__') + 29);
                    $to = strpos($text, '};');
                    $json = substr($text, 0, $to);
                    $data = json_decode($json."}",true);
                }
                if (isset($data['products']) ){
                    foreach ($data['products'] as $product) {
                            $response = $send->getData("https://www.trendyol.com".$product['url']);
                            if (gettype($response) == 'string'){
                                $found = preg_match('/window\.__PRODUCT_DETAIL_APP_INITIAL_STATE__=(.+);/', $response);
                                if ($found) {
                                    $text = substr($response, strpos($response, '__PRODUCT_DETAIL_APP_INITIAL_STATE__') + 37);
                                    $to = strpos($text, 'window.TYPageName') - 1;
                                    $json = substr($text, 0, $to);
                                    $productPage = json_decode($json,true);
                                }

                                if (isset($productPage['product'])){
                                    $image = $pars->getImages($productPage['product']['images']);
                                    $description = str_replace('[page="merchant_info"]POİNT GROUP E-TİCARET İTHALAT İHRACAT PA[/page]'," ",$pars->getDescription($productPage['product']['descriptions']));
                                    foreach ($productPage['product']['allVariants'] as $productItemVariant){
                                        $sizeVariant = explode("/", $productItemVariant['value']);
                                        $compound = $pars->getCompound($productPage['product']['attributes']);

                                        if (gettype($sizeVariant) == 'array'){
                                            foreach ($sizeVariant as $size){
                                                array_push($productExcel,
                                                    array_merge([
                                                        $productPage['product']['name'],
                                                        $productPage['product']['name'],
                                                        $productPage['product']['name'],
                                                        "[".implode(',',$compound)."]",
                                                        $description ?? '',
                                                        $description ?? '',
                                                        $description ?? '',
                                                        implode(',',$image),
                                                        $productItemVariant['barcode'],
                                                        0,
                                                        $productItemVariant['price'],
                                                        $productPage['product']['color'] ?? '',
                                                        $size == '' ?  $pars->getSize($productPage['product']['attributes']) : $size,
                                                        $productPage['product']['hasStock'] == true ? 100 : 0,
                                                        $this->import['status'] ?? 1,
                                                        $this->import['active'] ?? 'N',
                                                        $this->import['catalog'] ?? 0,
                                                        $this->import['user'] ?? 0,
                                                        $productPage['product']['brand']['name'] ?? '',
                                                        ["",0],
                                                        ["",0],
                                                        1,
                                                        $image[0] ?? '',
                                                        $image[1] ?? '',
                                                        $image[2] ?? '',
                                                        $image[3] ?? '',
                                                        $image[4] ?? '',
                                                        $image[5] ?? '',
                                                        $pars->getLength($productPage['product']),
                                                        $pars->getWidth($productPage['product']),
                                                        $pars->getHeight($productPage['product']),
                                                    ])
                                                );
                                            }
                                        }else{
                                            array_push($productExcel,
                                                array_merge([
                                                    $productPage['product']['name'],
                                                    $productPage['product']['name'],
                                                    $productPage['product']['name'],
                                                    "[".implode(',',$compound)."]",
                                                    $description ?? '',
                                                    $description ?? '',
                                                    $description ?? '',
                                                    implode(',',$image),
                                                    $productItemVariant['barcode'],
                                                    0,
                                                    $productItemVariant['price'],
                                                    $productPage['product']['color'] ?? '',
                                                    $productItemVariant['value'] == '' ?  $pars->getSize($productPage['product']['attributes']) : $productItemVariant['value'],

                                                    $productPage['product']['hasStock'] == true ? 100 : 0,

                                                    $this->import['status'] ?? 1,
                                                    $this->import['active'] ?? 'N',
                                                    $this->import['catalog'] ?? 0,
                                                    $this->import['user'] ?? 0,
                                                    $productPage['product']['brand']['name'] ?? '',
                                                    ["",0],
                                                    ["",0],
                                                    1,
                                                    $image[0] ?? '',
                                                    $image[1] ?? '',
                                                    $image[2] ?? '',
                                                    $image[3] ?? '',
                                                    $image[4] ?? '',
                                                    $image[5] ?? '',
                                                    $pars->getLength($productPage['product']),
                                                    $pars->getWidht($productPage['product']),
                                                    $pars->getHeight($productPage['product']),
                                                ])
                                            );
                                        }
                                    }
                                }
                        }
                    }
                }
            }
            $log->totalCount +=24;
            $log->save();
            $page++;
        }
            $attachment = Excel::raw(
                new ImportDataCatalogResultExport($productExcel, 'ru'),
                \Maatwebsite\Excel\Excel::XLSX
            );
            $fileResultName = Carbon::now().'-import-data.xlsx';
            Storage::disk('public-files')->put($fileResultName, $attachment);
            $end = Carbon::now();
            $minuteDiff = $end->diffInSeconds($start);



            $log->time = $minuteDiff;
            $log->totalCount = count($productExcel);
            $log->status = 'done';
            $log->file = $fileResultName;
            $log->save();

        $adminUser = User::where('id','=',1)
            ->first();

        if (!empty($adminUser->email)){
            Mail::to($adminUser->email)->send(new ParserMail($adminUser,$productExcel,$user->lang ?? 'tr',$this->import['url']));
        }

        try {
            if (!empty($this->request['user'])){
                $user = User::where('id','=',$this->request['user'] )
                    ->first();
                Mail::to($user->email)->send(new ParserMail($user,$productExcel,$user->lang ?? 'tr',$this->import['url']));
            }
        }catch (\Exception $e){

        }



        if (ob_get_length() == 0 ) {
            ob_start();
            $result = Excel::download(new ParseExport($productExcel,'tr'), 'Parse.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            ob_end_clean();

        }


    }
}
