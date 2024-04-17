<?php

namespace App\Fields;

use Illuminate\Support\Facades\Hash;

class PasswordField extends BasicField
{

    protected static $order = false;
    protected static $search = false;

    public function save($data)
    {
        if (!empty($data[$this->field])) {
            return [$this->field => Hash::make($data[$this->field])];
        } else {
            return [];
        }
    }

}
