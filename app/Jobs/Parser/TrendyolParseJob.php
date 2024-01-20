<?php

namespace App\Jobs\Parser;

use App\Exports\ParseExport;
use App\Mail\ParserMail;
use App\Models\User;
use App\Requests\Trendyol\TrendyolParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TrendyolParseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $pars = new TrendyolParser();
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

        while (1 >= $page){

            $url = $this->import['url']."&pi=".$page;

            $pageCategory = $pars->getPageResponse($url);
            $found = preg_match('/window\.__SEARCH_APP_INITIAL_STATE__=(.+);/', $pageCategory);


            if ($found) {
                $text = substr($pageCategory, strpos($pageCategory, '__SEARCH_APP_INITIAL_STATE__') + 29);
                $to = strpos($text, '};');
                $json = substr($text, 0, $to);
                $data = json_decode($json."}",true);
            }

            if (isset($data['products']) ){
                foreach ($data['products'] as $product) {
                    $productPage = $pars->parse($product['url']);
                    if (isset($productPage['product'])){
                        $image = $pars->getImages($productPage['product']['images']);
                        $description = $pars->getDescription($productPage['product']['descriptions']);

                        foreach ($productPage['product']['allVariants'] as $productItemVariant){
                            $sizeVariant = explode("/", $productItemVariant['value']);
                            $compound = $pars->getCompound($productPage['product']['contentDescriptions'][0]['description']);

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
                                            $size == '' ?  'standart' : $size,
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
                                            $productItemVariant['value'] == '' ?  'standart' : $productItemVariant['value'],

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
                                        ])
                                    );
                            }
                        }

                    }
                }
            }

            $page++;
        }

        $adminUser = User::where('id','=',1)
            ->first();
        if (!empty($adminUser->email)){
            Mail::to($adminUser->email)->send(new ParserMail($adminUser,$productExcel,$user->lang ?? 'tr',$this->import['url']));
        }

        if (!empty($this->request['user'])){
            $user = User::where('id','=',$this->request['user'] )
                ->first();
            Mail::to($user->email)->send(new ParserMail($user,$productExcel,$user->lang ?? 'tr',$this->import['url']));
        }
        if (ob_get_length() == 0 ) {
            ob_start();
            $result = Excel::download(new ParseExport($productExcel,'tr'), 'Parse.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            ob_end_clean();

        }


    }
}
