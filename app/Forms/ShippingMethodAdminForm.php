<?php

namespace App\Forms;

use App\Fields\CatalogField;
use App\Fields\DateTimeField;
use App\Fields\ImagesField;
use App\Fields\SelectField;
use App\Fields\TextboxField;

class ShippingMethodAdminForm extends BasicForm
{
    public static string $formNamePref = "shipping_method.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
    ];
}
