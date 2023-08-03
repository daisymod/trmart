<?php

namespace App\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class BasicField
{
    public string $field;
    public string $default;
    protected Model $record;
    public bool $readOnly  = false;

    public function __construct(string $field, Model &$record, $config = [])
    {
        $this->field = $field;
        $this->record = $record;
        foreach ($config as $k => $i) {
            $this->{$k} = $i;
        }
    }

    public function add(array $data)
    {
        return $this->standart($data, "add");
    }

    public function edit(array $data)
    {
        return $this->standart($data, "edit");
    }

    public function save(array $data)
    {
        if (array_key_exists($this->field, $data)) {
            return [$this->field => trim($data[$this->field])];
        } else {
            return [];
        }
    }

    public function find(array $data)
    {
        return $this->standart($data, "find");
    }

    protected function standart(array $data, $action)
    {
        $value = $this->getValue($data);
        $class = $this;
        $readonly = $this->readOnly;
        return view("fields." . class_basename($this), compact("action", 'readonly',"value", "class"))->toHtml();
    }

    public function afterSave(array $data)
    {

    }

    public function createFind(Builder $db, array $data)
    {
        if (!empty($data[$this->field])) {
            if (env("DB_CONNECTION") == "pgsql") {
                $db = $db->where($this->field, 'ILIKE', '%' . $data[$this->field] . '%');
            } else {
                $db = $db->where($this->field, 'LIKE', '%' . $data[$this->field] . '%');
            }
        }
        return $db;
    }

    protected function getValue(array $data)
    {

        $value = null;
        if (isset($data[$this->field])) {
            $value = $data[$this->field];
        } elseif (isset($this->default)) {
            $value = $this->default;
        }
        return $value;
    }

}
