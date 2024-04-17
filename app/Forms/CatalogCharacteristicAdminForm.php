<?php

namespace App\Forms;

use App\Fields\CatalogField;
use App\Fields\DateTimeField;
use App\Fields\ImagesField;
use App\Fields\SelectField;
use App\Fields\TextboxField;

class CatalogCharacteristicAdminForm extends BasicForm
{
    public static string $formNamePref = "catalog_characteristic.form.";
    protected bool $usePosition = true;
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_tr" => [TextboxField::class],
        "name_kz" => [TextboxField::class],
        "field" => [TextboxField::class],

    ];
}
