<?php

namespace App\Forms;

use App\Fields\CatalogCharacteristicField;
use App\Fields\ImagesField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class CatalogCharacteristicItemAdminForm extends BasicForm
{
    public static string $formNamePref = "catalog_characteristic_item.form.";
    protected bool $usePosition = true;
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_tr" => [TextboxField::class],
        "name_kz" => [TextboxField::class],
        "image" => [ImagesField::class],
        "catalog_characteristic" => [CatalogCharacteristicField::class, "multiple" => false]
    ];
}
