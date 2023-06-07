<?php


namespace App\Fields;


class FileField extends BasicField
{
    public function save($data)
    {
        $value = json_decode($this->record[$this->field]);
        if (request()->hasFile($this->field)) {
            $file = request()->file($this->field);
            $value = [
                "name" => $file->getClientOriginalName(),
                "ext" => $file->getClientOriginalExtension(),
                "path" => $file->store("public/catalog"),
            ];
        }
        return [$this->field => json_encode($value)];
    }

    protected function standart($data, $action)
    {
        $value = [];
        if (request()->hasFile($this->field)) {
            $file = request()->file($this->field);
            $value = [
                "name" => $file->getClientOriginalName(),
                "ext" => $file->getClientOriginalExtension(),
                "path" => $file->store("public/catalog"),
            ];
        } elseif (!empty($data[$this->field])) {
            $value = json_decode($data[$this->field], true);
        }
        $class = $this;
        return view("fields." . class_basename($this), compact("action", "value", "class"))->toHtml();
    }
}
