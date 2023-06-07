<?php

namespace App\Fields;

class TextboxField extends BasicField
{
    public string $type = "text";
    public string $step = "1";
    public array $datalist = [];
    public bool $readOnly  = false;
}
