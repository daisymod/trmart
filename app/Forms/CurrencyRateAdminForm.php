<?php

namespace App\Forms;

use App\Fields\CurrencyField;
use App\Fields\TextboxField;

class CurrencyRateAdminForm extends BasicForm
{
    public static string $formNamePref = "currency_rate.form.";
    public static array $formFields = [
        "currency" => [CurrencyField::class],
        "currency_to" => [CurrencyField::class],
        "rate_start" => [TextboxField::class, "type"=>"number", "step" => "0.01"],
        "rate_end" => [TextboxField::class, "type"=>"number", "step" => "0.01"],
    ];
}
