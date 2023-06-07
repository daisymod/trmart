<?php

namespace App\Forms;

use App\Fields\CatalogCharacteristicField;
use App\Fields\CatalogField;
use App\Fields\MerchantField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class CatalogAdminForm extends BasicForm
{
    public static string $formNamePref = "catalog.form.";
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_tr" => [TextboxField::class],
        "name_kz" => [TextboxField::class],
        "commission" => [TextboxField::class, "type"=>"number", "step" => "0.01", "default" => "0"],
        "parent" => [CatalogField::class],
        //"merchant" => [MerchantField::class],
        "characteristics" => [CatalogCharacteristicField::class, "basic" => false],
    ];
}
