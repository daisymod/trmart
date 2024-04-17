<?php

namespace App\Forms;

use App\Traits\FormModelTrait;
use App\Fields\PasswordField;
use App\Fields\LocationField;
use App\Fields\TextareaField;
use App\Traits\DNDModelTrait;
use App\Fields\CatalogField;
use App\Fields\IntegerField;
use App\Fields\TextboxField;
use App\Fields\SelectField;
use App\Fields\BasicField;
use App\Fields\DateField;
use App\Models\Merchant;

class CustomerEditForm extends BasicForm
{

    public static string $formNamePref = "customer.form.";
    public static array $formFields = [
        "address_invoice"   => [TextboxField::class, "find" => false],
        "house_number"      => [TextboxField::class, "find" => false],
        "room"              => [TextboxField::class, "find" => false],
        "birthday"          => [TextboxField::class, "type" => "date"],
        "whatsapp"          => [TextboxField::class, "type" => "tel"],
        "telegram"          => [TextboxField::class],
        "gender"            => [SelectField::class,
            "data"          => [
                "0"         => "Муж",
                "1"         => "Жен"
            ],
            "find"          => false],
        "country_id"        => [SelectField::class],
        "region_id"         => [SelectField::class],
        "area_id"           => [SelectField::class],
        "city_id"           => [SelectField::class],
        "phone"             => [TextboxField::class, "type" => "tel"],
        "email"             => [TextboxField::class, "type" =>"email"],
        "name"              => [TextboxField::class, "required" => true],
        "m_name"            => [TextboxField::class],
        "s_name"            => [TextboxField::class],
        "postcode_id"       => [SelectField::class],
        "postcode"          => [TextboxField::class],
        "country_title"     => [SelectField::class],
        "region_title"      => [SelectField::class],
        "area_title"        => [SelectField::class],
        "city_title"        => [SelectField::class],
        "role" => [SelectField::class, "data" => [
            "user" => "Пользователь",
            "admin" => "Админ",
            "logist" => "Логист",
        ]],
        "active" => [SelectField::class, "data" => [
            "Y" => 'Y',
            "N" => 'N',
        ]],
    ];
}
