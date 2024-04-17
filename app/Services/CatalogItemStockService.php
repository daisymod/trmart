<?php

namespace App\Services;

use App\Forms\CatalogItemAdminForm;
use App\Forms\CatalogItemAdminSearchForm;
use App\Forms\CatalogItemMerchantForm;
use App\Forms\MerchantCatalogItemMerchantForm;
use App\Forms\SearchCatalogItemForm;
use App\Models\CatalogItem;
use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogItemStockService
{
    public function list($stockStart = 0, $stockEnd = 0, Request $request)
    {
        $form = $this->getFormSearch(new CatalogItem());

        if (Auth::user()->role == "admin") {
            $records = CatalogItem::query();
        } else {
            $records = CatalogItem::query()->where("user_id", Auth::user()->id);
        }
        if (!empty($stockStart)) {
            $records = $records->where("stock", ">=", $stockStart);
        }
        if (!empty($stockEnd)) {
            $records = $records->where("stock", "<=", $stockEnd);
        }

        if (!empty($request->price_from)){
            $records->where("price",'>=', $request->price_from);
        }

        if (!empty($request->price_to)){
            $records->where("price",'<=', $request->price_to);
        }

        if (!empty($request->article)){
            $records->where("article",'=', $request->article);
        }

        if (!empty($request->barcode)){
            $records->where("barcode",'=', $request->barcode);
        }


        if (!empty($request->status)){
            $records->where("status",'=', $request->status == 4 ? 0 : $request->status);
        }

        if (!empty($request->active)){
            $records->where("active",'=', $request->active);
        }

        if (!empty($request->user)){
            $records->where("user_id",'=', $request->user);
        }

        if (!empty($request->name)){
            $records->where("name_".app()->getLocale(),'LIKE', '%'.$request->name.'%');
        }

        $records = $form->formCreateFind($records, request()->all());
        $records = $records->paginate($request->limit ?? 10);
        $form = $form->formRenderFind(request()->all());

        foreach ($records as $record){
            $count = ProductItem::where('item_id','=',$record->id)
                ->sum('count');
            $record->stock = $count;
        }

        return compact("records", "form");
    }

    public function save($stocks)
    {
        foreach ($stocks as $id=> $stock) {
            $item = CatalogItem::query()
                ->where("id", $id);
            if (Auth::user()->role <> "admin") {
                $item = $item->where("user_id", Auth::user()->id);
            }
            $item->update(["stock" => $stock]);
        }
    }

    public function getForm(Model $record)
    {
        if (Auth::user()->role == "admin") {
            $form = new CatalogItemAdminForm($record);
        } else {
            $record->setAttribute("user_id", Auth::user()->id);
            $form = new CatalogItemMerchantForm($record);
        }
        return $form;
    }

    public function getFormEdit(Model $record){
        if (Auth::user()->role == "admin") {
            $form = new CatalogItemAdminForm($record);
        } else {
            $record->setAttribute("user_id", Auth::user()->id);
            $form = new MerchantCatalogItemMerchantForm($record);
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
}
