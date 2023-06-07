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

class MerchantAdminForm extends BasicForm
{

    public static string $formNamePref = "merchant.form.";
    public static array $formFields = [

    ];

    public static function statusText($value)
    {
        return static::$formFields["status"]["data"][$value];
    }
}
