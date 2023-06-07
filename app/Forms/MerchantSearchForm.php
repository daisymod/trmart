<?php

namespace App\Forms;


use App\Fields\AreaField;
use App\Fields\CatalogField;
use App\Fields\CityField;
use App\Fields\LegalCityField;
use App\Fields\LocationField;
use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class MerchantSearchForm extends MerchantAdminForm
{

    protected function formGetFields($action): array
    {


        static::$formFields = array_merge(static::$formFields, [
            "phone" => [TextboxField::class, "type" => "tel"],
            "email" => [TextboxField::class, "type"=>"email"],
            "company_name" => [TextboxField::class, "find" => false],
            "shop_name" => [TextboxField::class, "find" => false],
            "first_name" => [TextboxField::class, "find" => false],
            "last_name" => [TextboxField::class, "find" => false],
            'tax_office' => [TextboxField::class, "find" => false],
            "tckn" => [TextboxField::class],
            "vkn" => [TextboxField::class],
            "iban" => [TextboxField::class],
            "sale_category" => [CatalogField::class, "level1Only" => true, "selectParent" => true, "multiple" => true],

            "status" => [SelectField::class, "data" => [
                "0" => trans('system.verif1'),
                "1" => trans('system.verif2'),
                "2" => trans('system.verif3'),
                "3" => trans('system.verif4')
            ], "edit" => true, "add" => true],

            "active" => [SelectField::class, "data" => [
                "Y" => trans('system.yes'),
                "N" => trans('system.no'),
            ]],
        ]);

        return parent::formGetFields($action);
    }

}
