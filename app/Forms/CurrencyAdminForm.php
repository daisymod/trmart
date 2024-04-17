<?php

namespace App\Forms;

use App\Fields\TextareaField;
use App\Fields\TextboxField;

class CurrencyAdminForm extends BasicForm
{
    public static string $formNamePref = "currency.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
    ];
}
