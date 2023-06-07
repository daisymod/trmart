<?php

namespace App\Forms;

use App\Fields\CatalogField;
use App\Fields\DateTimeField;
use App\Fields\ImagesField;
use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;


class ParserAdminForm extends BasicForm
{
    public static string $formNamePref = "catalog_item.form.";



    public static array $formFields = [
        "url" => [TextboxField::class],

        "catalog" => [CatalogField::class, "selectParent" => true],
    ];
}
