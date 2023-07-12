<?php

namespace App\Fields;

use App\Forms\BasicForm;
use App\Forms\CatalogItemMerchantForm;
use App\Forms\CurrencyAdminForm;
use App\Forms\ShippingMethodAdminForm;
use App\Models\CatalogItemDynamicCharacteristic;
use App\Models\Compound;
use App\Models\ItemCompoundTable;
use App\Models\ItemCompound;
use App\Models\ShippingMethod;
use App\Services\LanguageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CompoundField extends RelationField
{
    protected static Model|string $model = ItemCompound::class;
    protected BasicForm|string $form = CatalogItemMerchantForm::class;
    protected array $findFields = ["name_ru"];
    public array $datalist = [];

    public bool $big = false;

    public function save(array $data)
    {
        if (array_key_exists($this->field, $data)) {
            $values = [];
            foreach (LanguageService::$lang as $k => $i) {
                $values["{$this->field}_{$k}"] = trim($data[$this->field][$k]);
            }
            return $values;
        } else {
            return [];
        }
    }

    protected function getValue(array $data)
    {
        $value = [];
        foreach (LanguageService::$lang as $k => $i) {
            $field = "{$this->field}_{$k}";
            $value[$k] = null;
            if (isset($data[$field])) {
                $value[$k] = $data[$field];
            } elseif (!empty($this->default[$k])) {
                $value[$k] = $this->default[$k];
            }
        }
        return $value;
    }

    public function createFind(Builder $db, array $data)
    {

        if (!empty($data[$this->field])) {
            $field = $this->field . "_" . LanguageService::getLang();
            if (env("DB_CONNECTION") == "pgsql") {
                $db = $db->where($field, 'ILIKE', '%' . $data[$this->field] . '%');
            } else {
                $db = $db->where($field, 'LIKE', '%' . $data[$this->field] . '%');
            }
        }
        return $db;
    }

    public function actionInit(): string{

        return view("fields_compound")->render();
    }

    protected function standart($data, $action)
    {
        if (isset($data['id'])) {

            $compound = ItemCompoundTable::where('item_id', '=', $data['id'])
                ->with('compound')
                ->get();
        }else{
            $compound = [];
        }

        $data = Compound::all();

        $class = $this;

        return view("fields." . class_basename($this), compact("compound",'action','data','class'))->render();
    }

}
