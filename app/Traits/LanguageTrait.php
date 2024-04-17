<?php

namespace App\Traits;

use App\Services\LanguageService;

trait LanguageTrait
{
    public function lang($field)
    {
        $field = $field . "_" . LanguageService::getLang();
        return $this->$field;
    }
}
