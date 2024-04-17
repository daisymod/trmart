<?php

namespace App\Fields;

class ImagesField extends BasicField
{
    protected function standart($data, $action)
    {
        $field = $this->field;
        $value = [];
        if (!empty($data[$field]) and is_string($data[$field])) {
            $value = json_decode($data[$field], true);
        }
        if (!empty($data[$field]) and is_array($data[$field])) {
            foreach ($data[$field] as $k => $i) {
                $value[] = json_decode($i, true);
            }
        }
        $class = $this;
        return view("fields." . class_basename($this), compact("action", "value", "class", "field"))->toHtml();
    }

    public function save(array $data)
    {
        if (array_key_exists($this->field, $data)) {
            $data = $data[$this->field];
            foreach ($data as $k=>$i) {
                $data[$k] = json_decode($i);
            }
            return [$this->field =>  json_encode($data)];
        } else {
            return [];
        }
    }
}
