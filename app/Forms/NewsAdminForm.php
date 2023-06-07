<?php

namespace App\Forms;

use App\Fields\BasicField;
use App\Fields\DateField;
use App\Fields\DateTimeField;
use App\Fields\ImagesField;
use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Fields\TextboxField;
use App\Models\News;
use App\Models\Slider;
use App\Traits\FormModelTrait;

class NewsAdminForm extends BasicForm
{
    public static string $formNamePref = "news.form.";
    public static array $formFields = [
        "name" => [TextboxField::class],
        "category" => [TextboxField::class],
        "dt" => [DateField::class],
        "time" => [DateTimeField::class],
        "merchants" => [MerchantField::class, "multiple" => true],
        "body" => [TextareaField::class],

    ];

    protected function formGetFields($action): array
    {
        static::$formFields["category"]["datalist"] = News::query()->distinct()->pluck("category")->toArray();
        return parent::formGetFields($action);
    }
}
