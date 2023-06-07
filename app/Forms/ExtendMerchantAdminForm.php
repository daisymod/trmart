<?php

namespace App\Forms;


use App\Fields\AreaField;
use App\Fields\CatalogField;
use App\Fields\CityField;
use App\Fields\CountryField;
use App\Fields\LegalCityField;
use App\Fields\LocationField;
use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class ExtendMerchantAdminForm extends MerchantAdminForm
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
            "location" => [CountryField::class, "find" => false],
            "city_id" => [AreaField::class, "find" => true],

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
            "reg_form" => [SelectField::class, "data" => [
                "1" => trans('system.reg_form1'),
                "2" => trans('system.reg_form2'),
                "3" => trans('system.reg_form3'),
                "4" => trans('system.reg_form4'),
                "5" => trans('system.reg_form5'),
                "6" => trans('system.reg_form6'),
                "7" => trans('system.reg_form7'),
                "8" => trans('system.reg_form8'),
                "9" => trans('system.reg_form9'),
            ], "find" => false],

            "type_invoice" => [SelectField::class, "data" => [
                "2" => trans('system.type_invoice2'),
                "1" => trans('system.type_invoice1'),

            ], "find" => false],

            "reason" => [TextareaField::class],
        ]);

        return parent::formGetFields($action);
    }

}
