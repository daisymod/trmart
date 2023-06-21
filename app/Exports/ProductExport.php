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
            ->whereHas('catalogItem',function ($query){
                $query->where('id','>',1);
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
            trans('system.equipment'). 'TR',
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
            $compoundItemTr = '[';
            $compoundItemRu = '[';
            $compoundItemKz = '[';
            try{
                foreach ($item->catalogItem->compound as $compoundData){
                    $compoundItemTr .= $compoundData->name_tr.",".$compoundData->percent.",";
                    $compoundItemRu .= $compoundData->name_ru.",".$compoundData->percent.",";
                    $compoundItemKz .= $compoundData->name_kz.",".$compoundData->percent.",";
                }
            }catch (\Exception $e){
                $compoundItemTr = '"",0';
                $compoundItemRu = '"",0';
                $compoundItemKz = '"",0';
            }

            $compoundItemTr .= ']';
            $compoundItemRu .= ']';
            $compoundItemKz .= ']';
            $item_characteristic = array();

            foreach ($characteristics as $itemDataCharacteristic){
                $item_string = '';

                $value = CatalogItemDynamicCharacteristic::where('item_id',$item->catalogItem->id)
                        ->where('characteristic_id','=',$itemDataCharacteristic->id)
                        ->first();

                if (empty($value->id)){
                    $item_string = "['','','']";
                    array_push($item_characteristic,$item_string);
                }else{
                    $item_string = "[".$value->name_ru.",".$value->name_tr.",".$value->name_kz."]";
                    array_push($item_characteristic,$item_string);
                }

            }

            $getImg = json_decode($item->catalogItem->image, true);
            if (!empty($getImg)) {
                $image =  $getImg[0]["img"];
            } else {
                $image =  "/i/no_image.png";
            }

            array_push($result,array_merge([
                $item->catalogItem->name_ru,
                $item->catalogItem->name_tr,
                $item->catalogItem->name_kz,
                $compoundItemTr,
                $item->catalogItem->body_ru,
                $item->catalogItem->body_tr,
                $item->catalogItem->body_kz,
                'https://turkiyemart.com'.$image,
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
                $compoundItemRu,
                $compoundItemKz,
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
