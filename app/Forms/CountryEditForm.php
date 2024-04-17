<?php

namespace App\Forms;

use App\Fields\TextboxField;

class CountryEditForm extends BasicForm
{
    public static string $formNamePref = "country.form.";
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_en" => [TextboxField::class],
    ];
}
