<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class SearchCatalogItemForm extends CatalogItemMerchantForm
{
    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [

            "status" => [SelectField::class, "data" => [
                "4" => trans('system.verif1'),
                "1" => trans('system.verif2'),
                "2" => trans('system.verif3'),
                "3" => trans('system.verif4')
            ]],

            "brand" => [TextboxField::class,'value' => '' ],

            "active" => [SelectField::class, "data" => [
                "Y" => trans('system.active1'),
                "N" => trans('system.active2'),
            ]],

        ]);

        return parent::formGetFields($action);
    }

}
