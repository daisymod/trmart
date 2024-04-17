<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class ExtendCustomerSelfForm extends CustomerSelfForm
{

    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "gender"     => [SelectField::class,
                "data"   => [
                    "0"  => trans('system.i30'),
                    "1"  => trans('system.i31')
                ],
                "find"   => false],

        ]);

        return parent::formGetFields($action);
    }

}
