<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;

class ExtendSliderForm extends SliderAdminForm
{

    protected function formGetFields($action): array
    {
        return parent::formGetFields($action);
    }

}
