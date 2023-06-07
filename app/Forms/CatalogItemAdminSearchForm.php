<?php

namespace App\Forms;


use App\Fields\MerchantField;
use App\Fields\SelectField;
use App\Fields\TextareaField;
use App\Traits\FormModelTrait;
use Illuminate\Database\Eloquent\Builder;

class CatalogItemAdminSearchForm extends CatalogItemMerchantSearchForm
{
    protected function formGetFields($action): array
    {
        static::$formFields = array_merge(static::$formFields, [
            "user" => [MerchantField::class],
            "status" => [SelectField::class, "data" => [
                "4" => trans('system.verif1'),
                "1" => trans('system.verif2'),
                "2" => trans('system.verif3'),
                "3" => trans('system.verif4')
            ]],

            "active" => [SelectField::class, "data" => [
                "Y" => trans('system.active1'),
                "N" => trans('system.active2'),
            ]],

            "status_text" => [TextareaField::class, "find" => false],
        ]);

        return parent::formGetFields($action);
    }


    public function formCreateFind(Builder $db, array $date): Builder
    {
        foreach ($this->formGetFields("find") as $k => $i) {
            if ($k != 'price_from' || $k != 'price_to'){
                continue;
            }else{
                $db = $i->createFind($db, $date);
            }
        }
        return $db;
    }


}
