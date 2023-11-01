<?php

namespace App\Forms;


use App\Fields\IntegerField;
use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class CatalogItemAdminForm extends CatalogItemMerchantForm
{
    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "user" => [MerchantField::class,"multiple" => true],
            "sale" => [IntegerField::class],
            "status" => [SelectField::class, "data" => [
                "0" => trans('system.verif1'),
                "1" => trans('system.verif2'),
                "2" => trans('system.verif3'),
                "3" => trans('system.verif4')
            ]],

            "active" => [SelectField::class, "data" => [
                "Y" => trans('system.active1'),
                "N" => trans('system.active2'),
            ]],
            "reason" => [TextareaField::class, "find" => false],
        ]);

        return parent::formGetFields($action);
    }

}
