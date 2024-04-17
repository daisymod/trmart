<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class ExtendCatalogCharacteristicAdminForm extends CatalogCharacteristicAdminForm
{
    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "basic" => [SelectField::class, "data" => [
                "Y" => trans('system.i10'),
                "N" => trans('system.i11')
            ], "default" => "N"]

        ]);

        return parent::formGetFields($action);
    }

}
