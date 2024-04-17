<?php

namespace App\Http\Controllers;

use App\Services\LanguageService;

class LanguageController extends Controller
{
    public function setLanguage($language): \Illuminate\Http\RedirectResponse
    {
        LanguageService::setLang($language);
        return redirect()->back();
    }
}
