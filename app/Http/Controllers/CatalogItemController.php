<?php

namespace App\Http\Controllers;


use App\Actions\CatalogItemListPostAction;
use App\Exports\ProductExport;
use App\Http\Requests\CatalogItemEditPostRequest;
use App\Jobs\CatalogItemsExcelLoadJob;
use App\Mail\RejectNewItemMail;
use App\Mail\ResultImportMail;
use App\Models\CatalogCharacteristicItem;
use App\Models\CatalogItem;
use App\Models\MarketplaceBrands;
use App\Models\User;
use App\Services\CatalogItemActionService;
use App\Services\CatalogItemsExcelLoadService;
use App\Services\CatalogItemStockService;
use App\Services\CharacteristicService;
use App\Services\ColorService;
use App\Services\CompoundService;
use App\Services\ItemService;
use App\Services\MarketPlaceBrandService;
use App\Services\ProductItemSerice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class CatalogItemController
{

    public function __construct(protected ColorService $color,protected CompoundService $compound,protected ItemService $item,protected ProductItemSerice $product,protected CharacteristicService $characteristic)
    {
    }

    public function actionListGet(CatalogItemActionService $service,Request $request)
    {

        Gate::authorize("catalog-item-list");
        return view("catalog_item.list", $service->actionList($request));
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

        $data  = $this->item->create($request->all(),Auth::user());
        for ($index=0;$index<count($request->compound['ru']);$index++){
            if (isset($request->percent[$index])){
                $array = [
                    'name_ru' => $request->compound['ru'][$index] ?? null,
                    'name_tr' => $request->compound['tr'][$index] ?? null,
                    'name_kz' => $request->compound['kz'][$index] ?? null,
                    'percent' => $request->percent[$index],
                ];
                $this->compound->create($array,$data->id);
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
            for ($index=0;$index<30;$index++){
                if (!isset($request->addmore[$index])) {
                    continue;
                } else {
                    $this->product->create($request->addmore[$index], $data->id);
                }
            }
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
        
        $this->item->update($request->all(),$id,Auth::user());

        $this->compound->delete($id);
        $this->characteristic->delete($id);
        $this->product->delete($id);

        for ($index=0;$index<count($request->compound['ru']);$index++){
            if (isset($request->percent[$index])){
                $array = [
                    'name_ru' => $request->compound['ru'][$index] ?? null,
                    'name_tr' => $request->compound['tr'][$index] ?? null,
                    'name_kz' => $request->compound['kz'][$index] ?? null,
                    'percent' => $request->percent[$index],
                ];
                $this->compound->create($array,$id);
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
            for ($index=0;$index<30;$index++){
                if (!isset($request->addmore[$index])) {
                    continue;
                } else {
                    $this->product->create($request->addmore[$index], $id);
                }
            }
        }

        if ($request->status == 3 && !empty($request->reason) && Auth::user()->role == 'admin'){
            $user = User::where('id','=',$record->user_id)
                ->first();
            Mail::to($user->email)->send(new RejectNewItemMail($user, $request->all()));
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

        //Gate::authorize("catalog-item-excel-load");
        if (request()->hasFile("file") and request()->file("file")->getMimeType() == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
           $result = CatalogItemsExcelLoadJob::dispatch(CatalogItemsExcelLoadService::getArrayFromFile(request()->file("file")),Auth::user());
        }


        if (empty($result)){
            return response()->json(['html' =>
            "<div class='result-data'>".
                "<p class='contact-success'>".trans('system.importSuccess')."</p>".
                "<p class='contact'>".trans('system.importSuccess1')."</p>".
                "<p class='contact-2'>".trans('system.importSuccess3')."</p>".
                "<p class='contact-2'>".trans('system.importSuccess2')."</p>".

            "</div>"


            ]);
        }else{
            return response()->json(['html' =>
                "<div class='result-data'>".
                "<p class='contact-success'>".trans('system.importSuccess')."</p>".
                "<p class='contact'>".trans('system.importSuccess1')."</p>".
                "<p class='contact-2'>".trans('system.importSuccess3')."</p>".
                "<p class='contact-2'>".trans('system.importSuccess2')."</p>".
                "</div>"
            ]);
        }


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
