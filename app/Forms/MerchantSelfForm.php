<?php

namespace App\Forms;

use App\Fields\AreaField;
use App\Fields\CatalogField;
use App\Fields\CityField;
use App\Fields\DateField;
use App\Fields\BasicField;
use App\Fields\IntegerField;
use App\Fields\LegalCityField;
use App\Fields\LocationField;
use App\Fields\PasswordField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Models\Merchant;
use App\Traits\DNDModelTrait;
use App\Traits\FormModelTrait;

class MerchantSelfForm extends BasicForm
{

    public static string $formNamePref = "merchant.form.";
    public static array $formFields = [
        "phone" => [TextboxField::class, "type" => "tel"],
        "email" => [TextboxField::class, "type"=>"email"],
        "company_name" => [TextboxField::class, "find" => false],
        "shop_name" => [TextboxField::class, "find" => false],
        "first_name" => [TextboxField::class, "find" => false],
        "last_name" => [TextboxField::class, "find" => false],
        "patronymic" => [TextboxField::class, "find" => false],


        'tax_office' => [TextboxField::class, "find" => false],

        'legal_address_city' => [LegalCityField::class, "find" => false],
        'legal_address_street' => [TextboxField::class, "find" => false],
        'legal_address_office' => [TextboxField::class, "find" => false],
        'legal_address_number' => [TextboxField::class, "find" => false],


        'city' => [CityField::class, "find" => false],
        'street' => [TextboxField::class, "find" => false],
        'number' => [TextboxField::class, "find" => false],
        'office' => [TextboxField::class, "find" => false],


        "tckn" => [TextboxField::class],
        "vkn" => [TextboxField::class],
        "iban" => [TextboxField::class],
        "location" => [LocationField::class, "find" => false],
        "city_id" => [AreaField::class, "find" => true],

        "sale_category" => [CatalogField::class, "level1Only" => true, "selectParent" => true, "multiple" => true],

        "password" => [PasswordField::class, "find" => false],
    ];
}
