<?php

namespace App\Fields;

use App\Forms\BasicForm;
use App\Forms\ShippingMethodAdminForm;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Model;

class ShippingMethodField extends RelationField
{
    protected static Model|string $model = ShippingMethod::class;
    protected BasicForm|string $form = ShippingMethodAdminForm::class;
    protected array $findFields = ["name"];

    public function getModelQuery()
    {
        return static::$model::query()->orderBy("name");
    }

    protected function getValue(array $data)
    {
        return $this->record->{$this->field}()->getQuery()->pluck("shipping_methods.name", "shipping_methods.id")->toArray();
    }
}
