<?php

namespace App\Services;

use App\Models\Catalog;
use App\Models\CatalogItem;
use App\Models\Favorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopService
{
    public static function actionList(Request $request, $id)
    {
        $record = Catalog::query()
            ->with('recursiveChildren')
            ->findOrFail($id);

        $breadcrumbs = !empty($record->id) ? BreadcrumbService::get($record) : [];

        $array = array();




        array_push($array,intval($id));

        foreach ($record->recursiveChildren as $category){
                array_push($array,$category->id);
            foreach ($category->recursiveChildren as $last){
                array_push($array,$last->id);
            }
        }

        $items = CatalogItem::query()
            ->whereIn("catalog_id",$array)
            ->when(!empty($request->price_from),function ($query) use ($request) {
                $query->where("price",'>',$request->price_from);
            })
            ->when(!empty($request->price_to),function ($query) use ($request) {
                $query->where("price",'<',$request->price_to);
            })
            ->when(!empty($request->brand),function ($query) use ($request) {
                $query->where("brand", "=", $request->brand);
            })
            ->where("status", 2)
            ->where("active", "Y");

        $count = $items->count();
        $items = $items->paginate(50);


        return compact("record", "breadcrumbs", "items", "count");
    }

    public static function actionItem($id)
    {
        $record = CatalogItem::query()
            ->where("status", 2)
            ->where("active", "Y")
            ->with([
                'colors',
                'sizes',
                'lengths',
                'weights',
                'compound'
            ])
            ->findOrFail($id);

        $record->new_price = $record->price - ($record->price * $record->sale / 100) ;
        $catalog = $record->catalog;
        $breadcrumbs = BreadcrumbService::get($record);

        if (Auth::check()) {
            $isFavorite = Favorites::query()->where(['catalog_items_id' => $record->id, 'user_id' => Auth::user()->id])->count();
            return compact("record", "breadcrumbs", "catalog", "isFavorite");
        } else {
            return compact("record", "breadcrumbs", "catalog");
        }
    }

    public static function actionFind($find)
    {
        $items = CatalogItem::query()
            ->where("status", 2)
            ->where("active", "Y")
            ->when(!empty(request()->price_from),function ($query){
                $query->where("price",'>',request()->price_from);
            })
            ->when(!empty(request()->price_to),function ($query){
                $query->where("price",'<',request()->price_to);
            })
            ->when(!empty(request()->brand),function ($query) {
                $query->where("brand", "=", request()->brand);
            })
            ->where("name_" . LanguageService::getLang(), "LIKE", "%$find%");


        $count = $items->count();
        $items = $items->paginate(150);
        foreach ($items as $item){
            $item->new_price = $item->price - ($item->price * $item->sale / 100) ;
        }
        return compact("items", "count", "find");
    }

}
