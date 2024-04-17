<?php

namespace App\Forms;

use App\Fields\CatalogField;
use App\Fields\DateTimeField;
use App\Fields\ImagesField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Traits\FormModelTrait;

class PageAdminForm extends BasicForm
{
    public static string $formNamePref = "page.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
        "url" => [TextboxField::class],
        "body" => [TextareaField::class],
    ];
}
