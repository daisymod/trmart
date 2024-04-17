<?php

namespace App\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DateField extends BasicField
{
    public function save($data)
    {
        if (array_key_exists($this->field, $data)) {
            if (!empty($data[$this->field])) {
                return [$this->field => trim($data[$this->field])];
            } else {
                return [$this->field => DB::raw("NULL")];
            }
        } else {
            return [];
        }
    }

    public function createFind(Builder $db, array $data)
    {
        if (!empty($data[$this->field]["start"])) {
            $db = $db->where($this->field, ">=", $data[$this->field]["start"]." 00:00:00");
        }
        if (!empty($data[$this->field]["end"])) {
            $db = $db->where($this->field, "<=", $data[$this->field]["end"]." 23:59:59");
        }
        return $db;
    }
}
