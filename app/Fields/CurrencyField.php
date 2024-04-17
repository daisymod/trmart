<?php

namespace App\Fields;

use App\Forms\BasicForm;
use App\Forms\CurrencyAdminForm;
use App\Forms\ShippingMethodAdminForm;
use App\Models\Currency;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Model;

class CurrencyField extends RelationField
{
    protected static Model|string $model = Currency::class;
    protected BasicForm|string $form = CurrencyAdminForm::class;
    protected array $findFields = ["name"];

    public function getModelQuery()
    {
        return static::$model::query()->orderBy("name");
    }

    protected function getValue(array $data)
    {
        return $this->record->{$this->field}()->getQuery()->pluck("name", "id")->toArray();
    }
}
