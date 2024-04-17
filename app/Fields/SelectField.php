<?php

namespace App\Fields;

use Illuminate\Database\Eloquent\Builder;

class SelectField extends BasicField
{
    public array $data = [];
    public function createFind(Builder $db, array $data)
    {
        if (isset($data[$this->field])) {
            $db = $db->where($this->field, $data[$this->field]);
        }
        return $db;
    }
}
