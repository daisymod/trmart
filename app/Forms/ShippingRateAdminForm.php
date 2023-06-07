<?php

namespace App\Forms;

use App\Fields\ShippingMethodField;
use App\Fields\TextboxField;

class ShippingRateAdminForm extends BasicForm
{
    public static string $formNamePref = "shipping_rate.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
        "price" => [TextboxField::class, "type"=>"number", "step" => "0.01"],
        "period" => [TextboxField::class],
        "shipping_method" => [ShippingMethodField::class]
    ];
}
