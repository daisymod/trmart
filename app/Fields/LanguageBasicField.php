<?php

namespace App\Fields;

use App\Services\LanguageService;
use Illuminate\Database\Eloquent\Builder;

abstract class LanguageBasicField extends BasicField
{
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
}
