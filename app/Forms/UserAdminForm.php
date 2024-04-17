<?php

namespace App\Forms;

use App\Fields\ImagesField;
use App\Fields\LocationField;
use App\Fields\PasswordField;
use App\Fields\SelectField;
use App\Fields\TextboxField;
use App\Models\User;
use App\Traits\FormModelTrait;

class UserAdminForm extends BasicForm
{
    public static string $formNamePref = "customer.form.";
    public static array $formFields = [
        "name"              => [TextboxField::class, "required" => true],
        "m_name"            => [TextboxField::class],
        "s_name"            => [TextboxField::class],
        "phone" => [TextboxField::class, "type"=>"tel"],
        "role" => [SelectField::class, "data" => [
            "user" => "Пользователь",
            "logist" => "Логист",
        ]],
    ];

}
