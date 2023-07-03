<?php

namespace App\Forms;

use App\Fields\BrandField;
use App\Fields\CatalogField;
use App\Fields\CompoundField;
use App\Fields\CountryField;
use App\Fields\DateField;
use App\Fields\DynamicCharacteristicField;
use App\Fields\ImagesField;
use App\Fields\IntegerField;
use App\Fields\LanguageTextareaField;
use App\Fields\LanguageTextboxField;
use App\Fields\LocationField;
use App\Fields\MerchantField;
use App\Fields\ProductField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Models\CatalogCharacteristic;
use App\Services\LanguageService;
use App\Traits\FormModelTrait;

class CatalogItemMerchantForm extends BasicForm
{
    public static string $formNamePref = "catalog_item.form.";
    public static array $formFields = [
        "name" => [LanguageTextboxField::class],
        "catalog" => [CatalogField::class, "selectParent" => false],
        "brand" => [BrandField::class],
        "article" => [TextboxField::class,'readOnly' => true],
        "barcode" => [TextboxField::class],
        //"country" => [CountryField::class, "find" => true],
        "price" => [IntegerField::class,'type' => 'number'],
        "weight" => [IntegerField::class],
        "sale" => [IntegerField::class],
        "stock" => [TextboxField::class,'readOnly' => true],
       // "equipment" => [LanguageTextboxField::class, "find" => false],
        "image" => [ImagesField::class, "width" => 800, "height" => 1200, "find" => false],


        'compound' => [CompoundField::class, "find" => false],


        'product' => [ProductField::class, "find" => false],

        //'characteristic' => [DynamicCharacteristicField::class, "find" => false],

        "body" => [LanguageTextareaField::class, "find" => false],


    ];

    public static function statusText($value)
    {
        return trans('system.verif'.$value+1);
    }

    public static function activeText($value)
    {

        return static::$formFields["active"]["data"][$value];
    }

    protected function formGetFields($action): array
    {
       /*$fields = CatalogCharacteristic::query()
            ->orderBy("position")
            ->with("items")
            ->get();

        foreach ($fields as $k => $i) {
            static::$formFields[$i->field] = [
                $i->id == 19 ? TextboxField::class : SelectField::class,
                "find" => false,
                "name" => $i->lang("name"),
                "data" => $i->items->pluck("name_" . LanguageService::getLang(), "id")->toArray()
            ];
        }*/
        return parent::formGetFields($action);
    }
}

