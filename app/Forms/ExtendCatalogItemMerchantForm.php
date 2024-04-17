<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class ExtendCatalogItemMerchantForm extends CatalogItemMerchantForm
{

    protected function formGetFields($action): array
    {

        static::$formFields = array_merge(static::$formFields, [
            "active" => [SelectField::class, "data" => [
                "Y" => trans('system.active1'),
                "N" => trans('system.active2'),
            ]],
        ]);
        return parent::formGetFields($action);
    }

}
