<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class MerchantCatalogItemMerchantForm extends CatalogItemMerchantForm
{
    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "reason" => [TextareaField::class, "find" => false,'readOnly' => true],
        ]);

        return parent::formGetFields($action);
    }

}
