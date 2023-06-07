<?php

namespace App\Forms;

use App\Fields\CatalogField;
use App\Fields\DateTimeField;
use App\Fields\ImagesField;
use App\Fields\SelectField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class SliderAdminForm extends BasicForm
{
    public static string $formNamePref = "slider.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
        "link" => [TextboxField::class],
        "image" => [ImagesField::class, "width" => 1920, "height" => 400],
    ];
}
