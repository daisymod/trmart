<?php

namespace App\Services;

use App\Forms\CatalogItemAdminForm;
use App\Forms\CatalogItemAdminSearchForm;
use App\Forms\CatalogItemMerchantForm;
use App\Forms\CatalogItemMerchantSearchForm;
use App\Forms\ExtendCatalogItemMerchantForm;
use App\Forms\MerchantCatalogItemMerchantForm;
use App\Forms\SearchCatalogItemForm;
use App\Models\CatalogCharacteristicItem;
use App\Models\ParseStatistic;
use App\Services\ParseStatisticService;
use App\Models\CatalogItem;
use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogItemActionService
{
    public function actionList($request)
    {

        if (!empty($request['limit'])){
            session()->put('limit_catalog_item',$request['limit']);
        }else{
            session()->put('limit_catalog_item',100);
        }
        $parseStatistic = new ParseStatistic();
        $parseStatisticService = new ParseStatisticService($parseStatistic);
        $job = $parseStatisticService->getByUserNotEnd();
        $lang = LanguageService::getLang();

        $item = new CatalogItem();

        if (!empty($request['user'])){
            if (gettype($request['user']) == 'string'){
                $user= str_replace('[','',$request['user']);
                $user = str_replace(']','',$user);
                $user = str_replace('"','',$user);

                $item->user_id = explode(',',$user);
            }else {
                $item->user_id = $request['user'];
            }
        }

        if (!empty($request['catalog'])){
            if (gettype($request['catalog']) == 'string'){
                $catalog= str_replace('[','',$request['catalog']);
                $catalog = str_replace(']','',$catalog);
                $catalog = str_replace('"','',$catalog);
                $item->catalog_id = explode(',',$catalog);
            }else {
                $item->catalog_id = $request['catalog'];
            }
        }

        if (!empty($request['brand'])){
            $item->brand =  $request['brand'];
        }

        if (!empty($request['article'])){
            $item->article =  $request['article'];
        }

        if (!empty($request['barcode'])){
            $item->barcode =  $request['barcode'];
        }

        if (!empty($request['name'])){
            $item->name = $request['name'];
        }

        if (!empty($request['price_from'])){
            $item->price_from =  $request['price_from'];
        }

        if (!empty($request['price_to'])){
            $item->price_to =  $request['price_to'];
        }

        if (!empty($request['status'])){
            $item->status =  $request['status'];
        }

        if (!empty($request['active'])){
            $item->active =  $request['active'];
        }

        if (!empty($request['user'])){
            if (gettype($request['user']) == 'string'){
                $user= str_replace('[','',$request['user']);
                $user = str_replace(']','',$user);
                $user = str_replace('"','',$user);

                $item->user = explode(',',$user);
            }else {
                $item->user = $request['user'];
            }
        }

        $form = $this->getFormSearch($item);

        $sort = explode(".", request("sort_by", "updated_at.desc"));
        $records = CatalogItem::query()
            ->with('user.company')
            ->orderBy($sort[0], $sort[1]);
        if (Auth::user()->role == "merchant") {
            $records = $records->where("user_id", Auth::user()->id);
        }

        if (!empty($request['price_from'])){
            $records->where("price",'>=', $request['price_from']);
        }

        if (!empty($request['price_to'])){
            $records->where("price",'<=', $request['price_to']);
        }

        if (!empty($request['article'])){
            $records->where("article",'=', $request['article']);
        }

        if (!empty($request['barcode'])){
            $records->where("barcode",'=', $request['barcode']);
        }

        if (!empty($request['catalog'])){
            if (gettype($request['catalog']) == 'string'){
                $catalog = str_replace('[','',$request['catalog']);
                $catalog = str_replace(']','',$catalog);
                $catalog = str_replace('"','',$catalog);
                $records->whereIn("catalog_id",explode(',',$catalog));
            }else{
                $records->whereIn("catalog_id",$request['catalog']);
            }

        }

        if (!empty($request['user'])){
            if (gettype($request['user']) == 'string'){
                $user = str_replace('[','',$request['user']);
                $user = str_replace(']','',$user);
                $user = str_replace('"','',$user);
                $records->whereIn("user_id",explode(',',$user));
            }else{
                $records->whereIn("catalog_id",$request['user']);
            }

        }

        if (!empty($request['status'])){
            $records->where("status", $request['status'] == 4 ? 0 : $request['status']);
        }

        if (!empty($request['active'])){
            $records->where("active",'=', $request['active']);
        }

        if (!empty($request['brand'])){
            $records->where("brand",'=', $request['brand']);
        }

        if (!empty($request['name'])){
            $records->where("name_".app()->getLocale(),'LIKE', '%'.$request['name'].'%');
        }
        //$records = $form->formCreateFind($records, request()->all());

        $records = $records->paginate( session()->get('limit_catalog_item') ?? 100, ['*'], 'page', $request['page'] ?? 1);


        $records->getCollection()->transform(function ($product) {
            $count = ProductItem::where('item_id','=',$product->id)
                ->sum('count');
            $product->stock = $count;

            return $product;
        });
        $form = $form->formRenderFind(request()->all());
        $sortBy = [
            "name_{$lang}.asc" => trans('system.sort1'),
            "name_{$lang}.desc" => trans('system.sort2'),
            "price.asc" => trans('system.sort3'),
            "price.desc" => trans('system.sort4'),
            "status.asc" => trans('system.sort5'),
            "status.desc" => trans('system.sort6'),
            "created_at.asc" => trans('system.sort7'),
            "created_at.desc" => trans('system.sort8'),
        ];

        $color = CatalogCharacteristicItem::where('catalog_characteristic_id','=',15)
            ->get();

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            ->get();

        return compact("records", "form",'size','color', "sortBy",'job');
    }

    public function actionAddGet()
    {
        $color = CatalogCharacteristicItem::where('catalog_characteristic_id','=',15)
            ->get();

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            ->get();
        $form = $this->getForm(new CatalogItem());
        $form = $form->formRenderAdd();

        return compact("form",'size','color');
    }

    public function actionAddPost()
    {
        $form = $this->getForm(new CatalogItem());
        return $form->formSave(request()->all());
    }

    public function actionEditGet($record)
    {
        $count = ProductItem::where('item_id','=',$record->id)
            ->sum('count');
        $record->stock = $count;
        $form = $this->getFormEdit($record);
        $form = $form->formRenderEdit();
        $color = CatalogCharacteristicItem::where('catalog_characteristic_id','=',15)
            ->get();

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            ->get();


        return compact("record", "form",'size','color');
    }

    public function actionEditPost($record)
    {
        $form = $this->getForm($record);
        if (Auth::user()->role != "admin") {
            $record->setAttribute("status", 0);
        }
        $form->formSave(request()->all());
    }

    public function getForm(Model $record)
    {
        if (Auth::user()->role == "admin") {
            $form = new CatalogItemAdminForm($record);
        } else {
            $record->setAttribute("user_id", Auth::user()->id);
            $form = new ExtendCatalogItemMerchantForm($record);
        }
        return $form;
    }

    public function getFormEdit(Model $record){
        if (Auth::user()->role == "admin") {
            $form = new CatalogItemAdminForm($record);
        } else {
            $record->setAttribute("user_id", Auth::user()->id);
            $form = new ExtendCatalogItemMerchantForm($record);
        }
        return $form;
    }



    public function getFormSearch(Model $record)
    {
        if (Auth::user()->role == "admin") {
            $form = new CatalogItemAdminSearchForm($record);
        } else {
            $record->setAttribute("user_id", Auth::user()->id);
            $form = new SearchCatalogItemForm($record);
        }
        return $form;
    }

    public function actionFieldsGet()
    {

    }

    public function getNewItems(){
        $items =  CatalogItem::query()->orderByDesc('id')
            ->where("status", 2)
            ->where("active", "Y")
            ->limit(30)
            ->get();

        foreach ($items as $item){
            $images = json_decode($item->image);
            $item->main_img = $images[0]->img ?? '#';

            $item->new_price = $item->price - ($item->price * $item->sale / 100) ;
        }

        return $items;
    }


    public function topItems(){
        $items =  CatalogItem::query()->orderByDesc('id')
            ->where("status", 2)
            ->where("active", "Y")
            ->limit(30)
            ->with('order',function ($query){
            })
            ->inRandomOrder()
            ->get();

        foreach ($items as $item){
            $images = json_decode($item->image);
            $item->main_img = $images[0]->img ?? '#';

            $item->new_price = $item->price - ($item->price * $item->sale / 100) ;
        }

        return $items;
    }
}
