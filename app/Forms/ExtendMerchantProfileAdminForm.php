<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class ExtendMerchantProfileAdminForm extends MerchantAdminForm
{

    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "active" => [SelectField::class, "data" => [
                "Y" => "Да",
                "N" => "Нет",
            ]],
        ]);

        return parent::formGetFields($action);
    }

}
