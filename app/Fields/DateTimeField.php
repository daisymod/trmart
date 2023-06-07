<?php

namespace App\Fields;

use Illuminate\Support\Facades\DB;

class DateTimeField extends DateField
{
    protected function getValue(array $data)
    {
        return str_replace(" ", "T", $data[$this->field] ?? "");
    }
}
