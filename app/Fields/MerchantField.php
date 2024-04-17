<?php

namespace App\Fields;

use App\Forms\BasicForm;
use App\Forms\CatalogItemAdminForm;
use App\Forms\MerchantAdminForm;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Model;

class MerchantField extends RelationField
{
    protected static Model|string $model = Merchant::class;
    protected BasicForm|string $form = CatalogItemAdminForm::class;
    protected array $findFields = [
        "name", "phone", "email", "shot_name"
    ];

    public function getModelQuery()
    {
        return static::$model::query()->where("role", "merchant")->orderBy("name");
    }

    protected function getValue(array $data)
    {
        return $this->record->{$this->field}()->getQuery()->pluck("users.name", "users.id")->toArray();
    }
}
