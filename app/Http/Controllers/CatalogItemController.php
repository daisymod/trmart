<?php

namespace App\Http\Controllers;

use App\Events\ExcelEvent;
use App\Actions\CatalogItemListPostAction;
use App\Exports\ProductExport;
use App\Http\Requests\CatalogItemEditPostRequest;
use App\Jobs\CatalogItemsExcelLoadJob;
use App\Mail\RejectNewItemMail;
use App\Mail\ResultImportMail;
use App\Mail\VerifyProductMail;
use App\Models\AdminSettings;
use App\Models\CatalogCharacteristicItem;
use App\Models\CatalogItem;
use App\Models\Job;
use App\Models\MarketplaceBrands;
use App\Models\ProductItem;
use App\Models\ItemCompoundTable;
use App\Models\User;
use App\Requests\GptRequest;
use App\Services\AdminSettingsService;
use App\Services\CatalogItemActionService;
use App\Services\CatalogItemsExcelLoadService;
use App\Services\CatalogItemStockService;
use App\Services\CharacteristicService;
use App\Services\ColorService;
use App\Services\ParseStatisticService;
use App\Services\CompoundService;
use App\Services\ItemService;
use App\Services\MarketPlaceBrandService;
use App\Services\ProductItemSerice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CatalogItemController
{

    public function __construct(protected ParseStatisticService $parserStatistic,
                                protected ColorService $color,
                                protected CompoundService $compound,protected ItemService $item,
                                protected ProductItemSerice $product,protected CharacteristicService $characteristic,
                                protected AdminSettingsService $adminSettingsService
    )
    {
    }

    public function actionListGet(CatalogItemActionService $service,Request $request)
    {
        Gate::authorize("catalog-item-list");

        return view("catalog_item.list", $service->actionList($request->all()));
    }


    public function getPaginatePage(Request $request)
    {
        $items =  CatalogItem::query()->orderByDesc('id')
            ->where("status", 2)
            ->where("active", "Y")
            ->paginate(30);

        return response()->json(['items' =>
            $items
        ]);
    }


    public function actionListPost(CatalogItemListPostAction $action)
    {
        $action(request("ids"), request("action"));
    }

    public function actionAddGet(CatalogItemActionService $service)
    {
        Gate::authorize("catalog-item-add");
        $form = $service->getForm(new CatalogItem());
        $form = $form->formRenderAdd();
        $color = CatalogCharacteristicItem::where('catalog_characteristic_id','=',15)
            ->get();

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            ->get();

        return view("catalog_item.edit", ['form' => $form,'color' => $color,'size' => $size]);
    }

    public function actionAddPost(CatalogItemEditPostRequest $request, CatalogItemActionService $service)
    {
        Gate::authorize("catalog-item-add");

        $brand = MarketplaceBrands::where('name','=',$request->get('brand'))
                    ->first();

        if (empty($brand->name)){
            $marketBrandModel = new MarketplaceBrands();
            $brandService = new MarketPlaceBrandService($marketBrandModel);
            $brandService->create($request->get('brand'));
        }
        $requestGpt = new GptRequest();
        $array = $request->all();
        /*if (empty($array['name']['ru'])){
            $dataNameRu = $requestGpt->getData($array['name']['tr'],'Russian');
            if (isset($dataNameRu['data']['choices'][0]['message']['content'])){
                $array['name']['ru'] = $dataNameRu['data']['choices'][0]['message']['content'];
            }
        }

        if (empty($array['name']['kz'])){
            $dataNameKz = $requestGpt->getData($array['name']['tr'],'Kazakh');
            if (isset($dataNameKz['data']['choices'][0]['message']['content'])){
                $array['name']['kz'] = $dataNameKz['data']['choices'][0]['message']['content'];
            }
        }

        if (empty($array['body']['ru'])){
            $dataNameRu = $requestGpt->getData($array['body']['tr'],'Russian');
            if (isset($dataNameRu['data']['choices'][0]['message']['content'])){
                $array['body']['ru'] = $dataNameRu['data']['choices'][0]['message']['content'];
            }
        }

        if (empty($array['body']['kz'])){
            $dataNameKz = $requestGpt->getData($array['body']['tr'],'Kazakh');
            if (isset($dataNameKz['data']['choices'][0]['message']['content'])){
                $array['body']['kz'] = $dataNameKz['data']['choices'][0]['message']['content'];
            }
        }*/

        $data  = $this->item->create($array,Auth::user());

        for ($index=0;$index<count($request->compound);$index++){
            if (isset($request->percent[$index])){
                if (isset($request->percent[$index])){
                    ItemCompoundTable::create(
                        [
                            'item_id'       =>    $data->id,
                            'compound_id'   =>    $request->compound[$index],
                            'percent'       =>    $request->percent[$index]
                        ]
                    );
                }
            }

        }
        if (!isset($request->value[0])) {
            if (isset($request->characteristic)) {
                for ($index = 0; $index < 100; $index++) {
                    if (!isset($request->value['ru'][$index]) && !isset($request->value['ru'][$index]) && !isset($request->value['ru'][$index])){
                        continue;
                    }else{
                        $array = [
                            'characteristic_id' => $request->characteristic[$index],
                            'name_ru' => $request->value['ru'][$index],
                            'name_kz' => $request->value['kz'][$index],
                            'name_tr' => $request->value['tr'][$index],
                            'item_id' => $data->id,
                        ];
                        $this->characteristic->create($array);
                    }
                }
            }
        }

        if (isset($request->addmore)){
            for ($index=0;$index<100;$index++){
                if (!isset($request->addmore[$index])) {
                    continue;
                } else {
                    $this->product->create($request->addmore[$index], $data->id);
                }
            }
        }
        if (Auth::user()->role == 'merchant'){
            $dataUser = User::with('company')
                        ->where('id','=',Auth::user()->id)
                        ->first();
            $user = User::where('id','=',1)
                ->first();

            $dataMail = [
                'company_name' =>$dataUser->company->company_name,
                'shop_name' => $dataUser->company->shop_name,
                'first_name' => $dataUser->company->first_name,
                'last_name' =>  $dataUser->company->last_name,
                'email' => Auth::user()->email,
            ];

            Mail::to($user->email)->send(new VerifyProductMail($user, $dataMail));
        }

        return ["redirect" => route("catalog_item.list")];
    }

    public function actionEditGet($id, CatalogItemActionService $service)
    {
        $record = CatalogItem::query()->findOrFail($id);
        Gate::authorize("catalog-item-edit", $record);
        $color = $this->color->getAll();

        return view("catalog_item.edit", $service->actionEditGet($record));
    }

    public function actionEditPost($id, CatalogItemEditPostRequest $request, CatalogItemActionService $service)
    {

        $record = CatalogItem::query()->findOrFail($id);
        Gate::authorize("catalog-item-edit", $record);

        $brand = MarketplaceBrands::where('name','=',$request->get('brand'))
            ->first();

        if (empty($brand->name)){
            $marketBrandModel = new MarketplaceBrands();
            $brandService = new MarketPlaceBrandService($marketBrandModel);
            $brandService->create($request->get('brand'));
        }
        $requestGpt = new GptRequest();
        $array = $request->all();
       /* if (empty($array['name']['ru'])){
            $dataNameRu = $requestGpt->getData($array['name']['tr'],'Russian');
            if (isset($dataNameRu['data']['choices'][0]['message']['content'])){
                $array['name']['ru'] = $dataNameRu['data']['choices'][0]['message']['content'];
            }
        }

        if (empty($array['name']['kz'])){
            $dataNameKz = $requestGpt->getData($array['name']['tr'],'Kazakh');
            if (isset($dataNameKz['data']['choices'][0]['message']['content'])){
                $array['name']['kz'] = $dataNameKz['data']['choices'][0]['message']['content'];
            }
        }

        if (empty($array['body']['ru'])){
            $dataNameRu = $requestGpt->getData($array['body']['tr'],'Russian');
            if (isset($dataNameRu['data']['choices'][0]['message']['content'])){
                $array['body']['ru'] = $dataNameRu['data']['choices'][0]['message']['content'];
            }
        }

        if (empty($array['body']['kz'])){
            $dataNameKz = $requestGpt->getData($array['body']['tr'],'Kazakh');
            if (isset($dataNameKz['data']['choices'][0]['message']['content'])){
                $array['body']['kz'] = $dataNameKz['data']['choices'][0]['message']['content'];
            }
        }*/

        $this->item->update($array,$id,Auth::user());

        ItemCompoundTable::where('item_id','=',$id)
            ->delete();
        $this->characteristic->delete($id);
        $this->product->delete($id);

        for ($index=0;$index<count($request->compound);$index++){
            if (isset($request->percent[$index])){
                ItemCompoundTable::create(
                    [
                        'item_id'       =>    $id,
                        'compound_id'   =>    $request->compound[$index],
                        'percent'       =>    $request->percent[$index]
                    ]
                );
            }
        }

        if (!isset($request->value[0])) {
            if (isset($request->characteristic)) {
                for ($index = 0; $index < 100; $index++) {
                    if (!isset($request->value['ru'][$index]) && !isset($request->value['ru'][$index]) && !isset($request->value['ru'][$index])) {
                        continue;
                    } else {
                        $array = [
                            'characteristic_id' => $request->characteristic[$index],
                            'name_ru' => $request->value['ru'][$index],
                            'name_kz' => $request->value['kz'][$index],
                            'name_tr' => $request->value['tr'][$index],
                            'item_id' => $id,
                        ];
                        $this->characteristic->create($array);
                    }
                }
            }
        }

        if (isset($request->addmore)){
            for ($index=0;$index<100;$index++){
                if (!isset($request->addmore[$index])) {
                    continue;
                } else {

                    $array = $request->addmore[$index];

                    $result = [];
                    array_walk_recursive($array, function ($item, $key) use (&$result) {
                        if (str_contains($item, 'file')) {
                            $result[] = $item;
                        }
                    });
                    $array['image'] = $result;
                    $this->product->create($array, $id);
                }
            }
        }

        if ($request->status == 3 && !empty($request->reason) && Auth::user()->role == 'admin'){
            $user = User::where('id','=',$record->user_id)
                ->first();
            Mail::to($user->email)->send(new RejectNewItemMail($user, $request->all()));
        }

        if (Auth::user()->role == 'merchant'){
            $user = User::where('id','=',1)
                ->first();

            $userCompany = User::where('id','=',Auth::user()->id)
                ->with('company')
                ->first();

            $dataMail = [
                'company_name' => $userCompany->company()->company_name ?? null,
                'shop_name' => $userCompany->company()->shop_name ?? null,
                'first_name' => $userCompany->company()->first_name ?? null,
                'last_name' =>  $userCompany->company()->last_name ?? null,
                'email' => $userCompany->email ?? Auth::user()->email,
            ];

            Mail::to($user->email)->send(new VerifyProductMail($user, $dataMail));
        }

        return ["redirect" => route("catalog_item.list")];
    }

    public function actionVerificationSend($id)
    {
        $record = CatalogItem::query()->findOrFail($id);
        Gate::authorize("catalog-item-edit", $record);
        $record->status = 1;
        $record->save();
        return redirect(route("catalog_item.list"));
    }

    public function actionExcelLoad()
    {
        ini_set('max_execution_time', 999999999);
        set_time_limit(999999999);
        $existParse = $this->parserStatistic->getByUserNotEnd();

        if (!empty($existParse->id)){
            return response()->json(['html' =>
                "<div class='result-data'>".
                    "<p class='contact-2'>".trans('system.errorExist')."</p>".
                "</div>"
            ]);
        }

        $file = request()->file("file");
        $name = 'upload/'.Carbon::now()->format('Y-m-d_h-i');
        $path = Storage::disk("public")->put($name, $file);


        CatalogItemsExcelLoadJob::dispatch($path,Auth::user())->onQueue('excel'.rand(1,15));

        $reader = new Xlsx();
        $spreadsheet = $reader->load(request()->file("file"));
        $count = $spreadsheet->getActiveSheet()->getHighestRow() - 1;
        $time = $count * 3;

        return response()->json(['html' =>
            "<div class='result-data'>".
            "<p class='contact'>".trans('system.importSuccessV1') .$count
                .trans('system.importSuccessV13').
                Carbon::now()->addSecond($time).trans('system.importSuccessV14')."</p>".
                "<p class='contact-2'>".trans('system.importSuccessV11')."</p>".
                "<p class='contact-2'>".trans('system.importSuccessV12')."</p>".
                "</div>"
        ]);
    }

    public function actionDel($id)
    {
        $record = CatalogItem::query()->findOrFail($id);
        Gate::authorize("catalog-item-del", $record);
        $record->delete();
        return redirect(route("catalog_item.list"));
    }

    public function actionStockList(CatalogItemStockService $service,Request $request)
    {
        Gate::authorize("catalog-item-stock-list");
        return view("catalog_item.stock_list", $service->list(request("stock_start", 0), request("stock_end", 0),$request));
    }

    public function actionStockSave(CatalogItemStockService $service)
    {
        Gate::authorize("catalog-item-stock-save");
        $service->save(request("stock", []));
        return redirect(route("user.lk"));
    }

    public function actionExcelExport(Request $request){

        $user = User::where('id','=',$request->user_id)
                ->first();

        header("Content-type: application/vnd.ms-excel;charset:UTF-8");
        $response =  Excel::download(new ProductExport($user,$request->category_id ?? null), 'Items.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }

}
