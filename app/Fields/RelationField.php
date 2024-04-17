<?php

namespace App\Fields;

use App\Forms\BasicForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class RelationField extends BasicField
{
    protected static Model|string $model;
    protected BasicForm|string $form;
    protected array $findFields = [];
    public bool $multiple = false; //Можно выбрать несколько

    public function __construct(string $field = "", Model|null &$record = null, $config = [])
    {
        if ($record === null) {
            $record = new static::$model();
        }
        parent::__construct($field, $record, $config);
        $this->form = new $this->form($this->record);
        $this->form->setUseFields($this->findFields);
    }

    public function getFindHtml(): string
    {
        return view("fields.RelationField_find", ["form" => $this->form->formRenderFind([]), "class" => $this])->render();
    }

    public function actionList($date): string
    {
        $find = [];
        parse_str($date["find_data"], $find);
        $records = $this->getModelQuery();
        $records = $this->form->formCreateFind($records, $find);
        $records = $records->paginate(20);
        $config = request("config", []);
        return view("fields." . class_basename($this) . "_table", compact("records", "config"))->render();
    }


    public function save(array $data)
    {
        if (array_key_exists($this->field, $data) AND !$this->multiple) {
            return [$this->record->{$this->field}()->getForeignKeyName() => $data[$this->field][0] ?? 0];
        } else {
            return [];
        }
    }

    public function afterSave($data)
    {
        if (array_key_exists($this->field, $data) AND $this->multiple) {
            $this->record->{$this->field}()->sync($data[$this->field]);
        }
    }

    public function getModelQuery()
    {
        return static::$model::query()->orderBy("name");
    }

    public function createFind(Builder $db, array $data)
    {
        if (!empty($data[$this->field])) {
            $key = $this->record->{$this->field}()->getRelated()->getKeyName();
            $ids = $data[$this->field];
            $db = $db->whereHas($this->field, function ($query) use ($key, $ids) {
                $query->whereIn($key, $ids);
            });
        }
        return $db;
    }
}
