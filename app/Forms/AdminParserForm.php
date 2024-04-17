<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class AdminParserForm extends ParserAdminForm
{
    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "user" => [MerchantField::class],
            "status" => [SelectField::class, "data" => [
                "0" => trans('system.verif1'),
                "1" => trans('system.verif2'),
                "2" => trans('system.verif3'),
                "3" => trans('system.verif4')
            ], "edit" => true, "add" => true],

            "active" => [SelectField::class, "data" => [
                "Y" => trans('system.active1'),
                "N" => trans('system.active2'),
            ], "edit" => true, "add" => true],
        ]);

        return parent::formGetFields($action);
    }

}
