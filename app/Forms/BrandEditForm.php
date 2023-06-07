<?php

namespace App\Forms;

use App\Fields\CatalogCharacteristicField;
use App\Fields\CatalogField;
use App\Fields\ImagesField;
use App\Fields\MerchantField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class BrandEditForm extends BasicForm
{
    public static string $formNamePref = "catalog.form.";
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_tr" => [TextboxField::class],
        "name_kz" => [TextboxField::class],
        "link" => [TextboxField::class],
        "image" => [ImagesField::class, "width" => 200, "height" => 200],
    ];
}
