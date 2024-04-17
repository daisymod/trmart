<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class MerchantParserForm extends ParserAdminForm
{
    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [

            "status_text" => [TextareaField::class, "find" => false],
        ]);

        return parent::formGetFields($action);
    }

}
