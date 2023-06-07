<?php

namespace App\Fields;

use App\Forms\BasicForm;
use App\Forms\CatalogItemMerchantForm;
use App\Models\CatalogCharacteristicItem;
use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Model;

class ProductField extends RelationField
{
    protected static Model|string $model = ProductItem::class;
    protected BasicForm|string $form = CatalogItemMerchantForm::class;
    protected array $findFields = ["name_ru"];


    public function actionInit(): string{
        return view("ProductField")->render();
    }

    protected function standart($data, $action)
    {
        if (isset($data['id'])){
            $product = ProductItem::where('item_id','=',$data['id'])
                ->get();
        }else{
            $product = [];
        }


        $color = CatalogCharacteristicItem::where('catalog_characteristic_id','=',15)
            ->get();

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            ->get();

        return view("fields." . class_basename($this), compact("product",'color','size'))->render();
    }
}
