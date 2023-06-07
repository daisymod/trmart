<?php

namespace App\Forms;

use App\Fields\BasicField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

abstract class BasicForm
{
    protected Model $record;
    public static string $formNamePref = "basic.form.";
    public static array $formFields = [];
    protected array|null $useFields = null;
    protected bool $usePosition = false;

    public function __construct(Model $record)
    {
        $this->record = $record;
    }

    public function formRenderEdit(): array
    {

        $html = [];
        foreach ($this->formGetFields("edit") as $k => $i) {
            $html[$k] = $i->edit($this->record->attributesToArray());
        }

        return $html;
    }

    public function formRenderAdd(): array
    {
        $html = [];
        foreach ($this->formGetFields("add") as $k => $i) {
            $html[$k] = $i->add($this->record->attributesToArray());
        }
        return $html;
    }

    public function formRenderFind(array $data): array
    {
        $html = [];
        foreach ($this->formGetFields("find") as $k => $i) {
            $html[$k] = $i->find($data);
        }
        return $html;
    }

    public function formSave(array $data)
    {
        $fields = $this->formGetFields("save");
        foreach ($fields as $k => $i) {
            $value = $i->save($data);
            foreach ($value as $vk => $vi) {
                if ($vk === 'birthday' && $vi === '') {
                    $vi = null;
                    $this->record->setAttribute($vk, $vi);
                }
                $this->record->setAttribute($vk, $vi);
            }
        }
        if ($this->usePosition and !$this->record->exists) {
            $this->record->setAttribute("position", $this->record::query()->max("position") + 1);
        }
        $this->record->save();
        foreach ($fields as $k => $i) {
            $i->afterSave($data);
        }
        return $this;
    }

    /**
     * @return BasicField[]
     */
    protected function formGetFields($action): array
    {
        $components = [];
        foreach (static::$formFields as $field => $config) {
            if ($this->useFields === null or in_array($field, $this->useFields)) {
                if (!isset($config[$action]) or $config[$action] !== false) {
                    if (empty($config["name"])) {
                        $config["name"] = static::$formNamePref . $field;
                    }
                    $components[$field] = new $config[0]($field, $this->record, $config);
                }
            }
        }
        return $components;
    }

    public function formCreateFind(Builder $db, array $date): Builder
    {
        foreach ($this->formGetFields("find") as $k => $i) {
            $db = $i->createFind($db, $date);
        }
        return $db;
    }

    public function setUseFields($fields)
    {
        $this->useFields = $fields;
    }

    public function getAttribute($key)
    {
        return $this->record->getAttribute($key);
    }
}
