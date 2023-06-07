<?php

namespace App\Exports;

use App\Models\CatalogCharacteristic;
use App\Models\CatalogItem;
use App\Models\CatalogItemDynamicCharacteristic;
use App\Models\Order;
use App\Models\ProductItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ProductExport implements FromArray,WithColumnWidths,ShouldQueue
{

    public $user;
    public $category;
    public function __construct($user,$category)
    {
        $this->category = $category;
        $this->user = $user;
    }

    public function array(): array
    {
        $items = ProductItem::with(
            ['catalogItem', 'sizeData','colorData']
        )
            ->when($this->user->role == 'merchant',function ($q){
                $q->whereHas('catalogItem',function ($query){
                    $query->where('user_id','=',$this->user->id);
                });
            })
            ->get();

        $result = array();

        $lang = Auth::user()->lang ?? 'ru';
        app()->setLocale($lang);

        if ($this->category == null){
            $characteristics = CatalogCharacteristic::all();
        }else{
            $characteristics = CatalogCharacteristic::whereHas('catalog',function ($q){
                $q->where('catalog_id','=',$this->category);
            })->get();
        }

        $itemsData = array();

        foreach ($characteristics as $item){
            array_push($itemsData,$item->{'name_'.$lang});
        }

        $headers = [
            trans('system.name_ru'),
            trans('system.name_tr'),
            trans('system.name_kz'),
            trans('system.equipment'),
            trans('system.body_ru'),
            trans('system.body_tr'),
            trans('system.body_kz'),
            trans('system.images'),
            trans('catalog_item.form.barcode'),
            trans('system.sale'),
            trans('system.price'),
            trans('system.color'),
            trans('system.size'),
            trans('system.count'),
            trans('system.status'),
            trans('system.active'),
            trans('system.catalog'),
            trans('system.user'),
            trans('system.brand'),
            trans('system.equipment'). 'RUS',
            trans('system.equipment'). 'KZ',
            trans('catalog_item.form.weight'),
        ];



        array_push($result,array_merge($headers,$itemsData));

        foreach ($items as $item){
            $compoundItem = '';

            foreach ($item->catalogItem->compound as $compoundData){
                $compoundItem .= " ". $compoundData->percent . ' '. $compoundData->name_tr;
            }

            $item_characteristic = array();
            foreach ($characteristics as $itemDataCharacteristic){
                $value = CatalogItemDynamicCharacteristic::where('item_id',$item->catalogItem->id)
                        ->where('characteristic_id','=',$itemDataCharacteristic->id)
                        ->first();

                if (empty($value->id)){
                    array_push($item_characteristic,['','','']);
                }else{
                    array_push($item_characteristic,[$value->name_ru == null ? '' : $value->name_ru,$value->name_tr == null ? '' : $value->name_tr,$value->name_kz== null ? '' : $value->name_kz]);
                }

            }

            array_push($result,array_merge([
                $item->catalogItem->name_ru,
                $item->catalogItem->name_kz,
                $item->catalogItem->name_tr,
                $compoundItem,
                $item->catalogItem->body_ru,
                $item->catalogItem->body_kz,
                $item->catalogItem->body_tr,
                $item->catalogItem->image,
                $item->catalogItem->article,
                $item->catalogItem->sale,
                $item->catalogItem->price,
                $item->colorData->name_tr,
                $item->sizeData->name_tr,
                $item->count,
                $item->catalogItem->status,
                $item->catalogItem->active,
                $item->catalogItem->catalog_id,
                $item->catalogItem->user_id,
                $item->catalogItem->brand,
                $compoundItem,
                $compoundItem,
                $item->catalogItem->weight,
            ],$item_characteristic));
        }


        return [
            $result
        ];

    }



    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 25,
            'D' => 25,
            'E' => 25,
            'F' => 25,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 25,
            'K' => 25,
        ];
    }

}
