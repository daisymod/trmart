<?php

namespace App\Forms;

use App\Fields\TextboxField;

class CityEditForm extends BasicForm
{
    public static string $formNamePref = "city.form.";
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_en" => [TextboxField::class],
    ];
}
