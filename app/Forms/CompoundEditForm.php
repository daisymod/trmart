<?php

namespace App\Forms;

use App\Fields\TextboxField;


class CompoundEditForm extends BasicForm
{
    public static string $formNamePref = "catalog.form.";
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_tr" => [TextboxField::class],
        "name_kz" => [TextboxField::class],
    ];
}
