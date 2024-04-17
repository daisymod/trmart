<?php

namespace App\Forms;

use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class CommissionEditForm extends BasicForm
{
    public static string $formNamePref = "commission.form.";
    public static array $formFields = [
        "name_ru" => [TextboxField::class],
        "name_tr" => [TextboxField::class],
        "name_kz" => [TextboxField::class],
        "commission" => [TextboxField::class, "type"=>"number", "step" => "0.01", "default" => "0"],
    ];
}
