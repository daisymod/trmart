<?php

namespace App\Forms;

use App\Fields\ImagesField;
use App\Fields\LocationField;
use App\Fields\PasswordField;
use App\Fields\SelectField;
use App\Fields\TextboxField;
use App\Models\User;
use App\Traits\FormModelTrait;

class AdminAdminForm extends BasicForm
{
    public static string $formNamePref = "user.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
        "phone" => [TextboxField::class, "type"=>"tel"],
        "role" => [SelectField::class, "data" => [
            "user" => "Пользователь",
            "admin" => "Админ",
            "logist" => "Логист",
        ]],
        "password" => [PasswordField::class, "find" => false],
    ];
}
