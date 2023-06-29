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

        $field = 'image';
        $value = [];
        $index = 0;

        foreach ($product as $item){
            if (!empty($item->{$field}) and is_string($item->{$field})) {
                $value[$index] = json_decode($item->{$field}, true);
            }

            if (!empty($item->{$field}) and is_array($item->{$field})) {

                foreach ($item->{$field} as $k => $i) {
                    $value[$index] = json_decode($i, true);
                }
            }
            
            $index++;
        }


        $class = $this;

        $color = CatalogCharacteristicItem::where('catalog_characteristic_id','=',15)
            ->get();

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            ->get();

        return view("fields." . class_basename($this), compact("product",'color','size',"value", "class", "field"))->render();
    }

    public function save(array $data)
    {
        if (array_key_exists($this->field, $data)) {
            $data = $data[$this->field];
            foreach ($data as $k=>$i) {
                $data[$k] = json_decode($i);
            }
            return [$this->field =>  json_encode($data)];
        } else {
            return [];
        }
    }
}
