<?php

namespace App\Forms;

use App\Fields\CatalogField;
use App\Fields\DateField;
use App\Fields\BasicField;
use App\Fields\IntegerField;
use App\Fields\LocationField;
use App\Fields\PasswordField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Models\Merchant;
use App\Traits\DNDModelTrait;
use App\Traits\FormModelTrait;

class CustomerAdminForm extends BasicForm
{

    public static string $formNamePref = "customer.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
        "phone" => [TextboxField::class, "type"=>"tel"],
        //"address" => [TextboxField::class, "find" => false],
        "email" => [TextboxField::class, "type"=>"email"],

        "shop_name" => [TextboxField::class, "find" => false],
        "shot_name" => [TextboxField::class],
        //"full_name" => [TextboxField::class],
        //"bin" => [TextboxField::class, "find" => false],
        "reg_form" => [SelectField::class, "data" => [
            "" => "",
            "1" => "Anonim Şirketi ",
            "2" => "Şahıs Şirketi",
            "3" => "Limited Şirketi",
            "4" => "Kollektif Şirket",
            "5" => "Kooperatıf Şirket",
            "6" => "Adi Ortaklık",
            "7" => "Adi Komandit Şirket",
            "8" => "ESH Komandit Şirket",
            "9" => "Diğer",

        ], "find" => false],
        "tckn" => [TextboxField::class],
        "vkn" => [TextboxField::class],
        "iban" => [TextboxField::class],
        "location" => [LocationField::class, "find" => false],
        "district" => [TextboxField::class],
        "address_ur" => [TextboxField::class, "find" => false],
        "address_invoice" => [TextboxField::class, "find" => false],
        "address_return" => [TextboxField::class, "find" => false],
        "type_invoice" => [SelectField::class, "data" => [
            "0" => "",
            "1" => "Электронная счет фактура",
            "2" => "Бумажная счет фактура",
        ], "find" => false],
        "sale_category" => [CatalogField::class, "level1Only" => true, "selectParent" => true, "multiple" => true],
        //"inn" => [TextboxField::class],
        //"dt_reg" => [DateField::class],
        //"desc" => [TextareaField::class, "find" => false],
        //"body" => [TextareaField::class, "find" => false],

        "role" => [SelectField::class, "data" => [
            "user" => "Пользователь",
            "merchant" => "Мерчант",
            "admin" => "Админ",
            "logist" => "Логист",
            "dispatcher" => "Диспетчер"
        ], "find" => false],
        "status_text" => [TextareaField::class],
/*        "active" => [SelectField::class, "data" => [
            "Y" => "Да",
            "N" => "Нет",
        ]],*/
        //"rating" => [IntegerField::class, "find" => false],
        //"step" => [IntegerField::class, "find" => false],
        "password" => [PasswordField::class, "find" => false],
    ];

    public static function statusText($value)
    {
        return static::$formFields["status"]["data"][$value];
    }
}
