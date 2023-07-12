<?php

namespace App\Services;

use App\Models\Catalog;
use App\Models\CatalogCatalogCharacteristic;
use App\Models\CatalogCharacteristic;
use App\Models\CatalogItem;
use App\Models\Compound;
use App\Models\CatalogItemDynamicCharacteristic;
use App\Models\CurrencyRate;
use App\Models\Favorites;
use App\Models\ProductItem;
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

        if (\request()->currency_id == 1){
            $coefficient = CurrencyRate::where('id','=',2)->first()->rate_end;
        }else{
            $coefficient = 1;
        }



        $items = CatalogItem::query()
            ->whereIn("catalog_id",$array)
            ->when(!empty(request()->price_from),function ($query) use ($coefficient){
                $query->where("price",'>',request()->price_from  / $coefficient);
            })
            ->when(!empty(request()->price_to),function ($query) use ($coefficient){
                $query->where("price",'<',request()->price_to / $coefficient);
            })
            ->when(!empty(request()->brand),function ($query) {
                $query->whereIn("brand",gettype(request()->brand) == 'array' ? request()->brand : [request()->brand]);
            })

            ->when(!empty(request()->size),function ($query) {
                $query->whereHas('productsData',function ($q){
                    $q->whereIn("size",request()->size);
                });
            })
            ->when(!empty(request()->compound),function ($query) {
                $query->whereHas('compound.compound',function ($q){
                    if (Auth::check()){
                        switch (Auth::user()->lang){
                            case 'ru':
                                $q->whereIn("name_ru",request()->compound);
                                break;
                            case 'kz':
                                $q->whereIn("name_kz",request()->compound);
                                break;
                            case 'tr':
                                $q->whereIn("name_tr",request()->compound);
                                break;
                        }
                    }else{
                        $q->whereIn("name_ru",request()->compound);
                        $q->whereIn("name_kz",request()->compound);
                        $q->whereIn("name_tr",request()->compound);
                    }
                });
            })
            ->when(!empty(request()->item),function ($query) {
                foreach (request()->item as $key => $value){
                    $query->whereHas('dynamic',function ($q) use ($key,$value){
                        $q->where('characteristic_id','=',$key);
                        $q->where('name_ru','=',$value);
                        $q->orWhere('name_tr','=',$value);
                        $q->orWhere('name_kz','=',$value);
                    });
                }
            })
            ->when(!empty(request()->color),function ($query) {
                $query->whereHas('productsData',function ($q){
                    $q->whereIn("color",request()->color);
                });
            })
            ->where("status", 2)
            ->where("active", "Y");

        $searchIds = array();


        foreach($items->get()->toArray() as $item){
            array_push($searchIds,$item['id']);
        }

        $uniqueBrand = CatalogItem::query()
            ->whereIn("id", $searchIds)
            ->get()->unique('brand');

        $uniqueCompound = Compound::all();

        $uniqueColor = ProductItem::query()
            ->whereIn("item_id", $searchIds)
            ->with('colorData')
            ->get()->unique('color');

        $uniqueSize = ProductItem::query()
            ->whereIn("item_id", $searchIds)
            ->with('SizeData')
            ->get()->unique('size');


        $count = $items->count();
        $items = $items->paginate(50);

        foreach ($items as $item){
            $item->new_price = $item->price - ($item->price * $item->sale / 100) ;
        }

        $filter = CatalogCharacteristic::where('id','!=',15)
            ->where('id','!=',16)
            ->with('dynamicCharacteristic')
            ->whereHas('dynamicCharacteristic',function ($q) use ($searchIds){
                $q->whereIn("item_id", $searchIds);
            })
            ->get();

        return compact("record", "breadcrumbs", "items", "count",'uniqueBrand','uniqueColor','uniqueSize','filter','uniqueCompound');
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
                'compound',
                'compound.compound'
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
        if (\request()->currency_id == 1){
            $coefficient = CurrencyRate::where('id','=',2)->first()->rate_end;
        }else{
            $coefficient = 1;
        }

        $items = CatalogItem::query()
            ->where("status", 2)
            ->where("active", "Y")
            ->when(!empty(request()->price_from),function ($query) use ($coefficient){
                $query->where("price",'>',request()->price_from  / $coefficient);
            })
            ->when(!empty(request()->price_to),function ($query) use ($coefficient){
                $query->where("price",'<',request()->price_to / $coefficient);
            })
            ->when(!empty(request()->brand),function ($query) {
                $query->whereIn("brand",gettype(request()->brand) == 'array' ? request()->brand : [request()->brand]);
            })
            ->when(!empty(request()->compound),function ($query) {
                $query->whereHas('compound.compound',function ($q){
                    if (Auth::check()){
                        switch (Auth::user()->lang){
                            case 'ru':
                                $q->whereIn("name_ru",request()->compound);
                                break;
                            case 'kz':
                                $q->whereIn("name_kz",request()->compound);
                                break;
                            case 'tr':
                                $q->whereIn("name_tr",request()->compound);
                                break;
                        }
                    }else{
                        $q->whereIn("name_ru",request()->compound);
                        $q->whereIn("name_kz",request()->compound);
                        $q->whereIn("name_tr",request()->compound);
                    }

                });
            })
            ->when(!empty(request()->size),function ($query) {
                $query->whereHas('productsData',function ($q){
                    $q->whereIn("size",request()->size);
                });
            })
            ->when(!empty(request()->item),function ($query) {
                foreach (request()->item as $key => $value){
                    $query->whereHas('dynamic',function ($q) use ($key,$value){
                        $q->where('characteristic_id','=',$key);
                        $q->where('name_ru','=',$value);
                        $q->orWhere('name_tr','=',$value);
                        $q->orWhere('name_kz','=',$value);
                    });
                }
            })
            ->when(!empty(request()->color),function ($query) {
                $query->whereHas('productsData',function ($q){
                    $q->whereIn("color",request()->color);
                });
            })

            ->where("name_" . LanguageService::getLang(), "LIKE", "%$find%");

        $searchIds = array();


        foreach($items->get()->toArray() as $item){
            array_push($searchIds,$item['id']);
        }

        $uniqueBrand = CatalogItem::query()
            ->whereIn("id", $searchIds)
            ->get()->unique('brand');

        $uniqueCompound = Compound::all();

        $uniqueColor = ProductItem::query()
            ->whereIn("item_id", $searchIds)
            ->with('colorData')
            ->get()->unique('color');

        $uniqueSize = ProductItem::query()
            ->whereIn("item_id", $searchIds)
            ->with('SizeData')
            ->get()->unique('size');

        $count = $items->count();
        $items = $items->paginate(150);
        foreach ($items as $item){
            $item->new_price = $item->price - ($item->price * $item->sale / 100) ;
        }

        $filter = [];

        return compact("items", "count", "find",'uniqueBrand','uniqueColor','uniqueSize','filter','uniqueCompound');
    }

}
