<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class ExtendNewsAdminForm extends NewsAdminForm
{

    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "active" => [SelectField::class, "data" => [
                "Y" => trans('system.i12'),
                "N" => trans('system.i13'),
            ]],

        ]);

        return parent::formGetFields($action);
    }

}
