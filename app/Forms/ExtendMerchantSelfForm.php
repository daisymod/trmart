<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class ExtendMerchantSelfForm extends MerchantSelfForm
{

    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [

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

        ]);




        return parent::formGetFields($action);
    }

}
