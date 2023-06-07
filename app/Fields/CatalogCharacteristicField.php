<?php

namespace App\Fields;

use App\Forms\BasicForm;
use App\Forms\CatalogCharacteristicAdminForm;
use App\Forms\ShippingMethodAdminForm;
use App\Models\CatalogCharacteristic;
use App\Models\ShippingMethod;
use App\Services\LanguageService;
use Illuminate\Database\Eloquent\Model;

class CatalogCharacteristicField extends RelationField
{
    protected static Model|string $model = CatalogCharacteristic::class;
    protected BasicForm|string $form = CatalogCharacteristicAdminForm::class;
    protected array $findFields = ["name"];
    public bool $multiple = true; //Можно выбрать несколько
    public null|bool $basic = null;

    protected function getValue(array $data)
    {
        return $this->record->{$this->field}()
            ->getQuery()
            ->orderBy("catalog_characteristics.position")
            ->pluck("catalog_characteristics.name_" . LanguageService::getLang(), "catalog_characteristics.id")
            ->toArray();
    }

    public function getModelQuery()
    {
        $query = static::$model::query()->orderBy("position");
        /*        if ($this->basic !== null) {
                    if ($this->basic === true) {
                        $query = $query->where("basic", "Y");
                    } else {
                        $query = $query->where("basic", "N");
                    }
                }*/
        return $query->where("basic", "N");
    }
}
