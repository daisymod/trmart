<?php

namespace App\Fields;

use Illuminate\Database\Eloquent\Builder;

class IntegerField extends BasicField
{

    protected static $order = true;
    protected static $search = true;

    public function save($data)
    {
        if (!empty($data[$this->field])) {
            return [$this->field => trim($data[$this->field])];
        } else {
            return [$this->field => "0"];
        }
    }
    public function createFind(Builder $db, array $data)
    {
        if (!empty($data[$this->field]["start"])) {
            $db = $db->where($this->field, ">=", $data[$this->field]["start"]);
        }
        if (!empty($data[$this->field]["end"])) {
            $db = $db->where($this->field, "<=", $data[$this->field]["end"]);
        }
        return $db;
    }
}
