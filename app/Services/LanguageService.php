<?php

namespace App\Services;

class LanguageService
{
    static public array $lang = [
        "ru" => [
            "cod" => "RUS",
            "name" => "Русский"
        ],
        "tr" => [
            "cod" => "TUR",
            "name" => "Турецкий"
        ],
        "kz" => [
            "cod" => "KAZ",
            "name" => "Казахский"
        ]
    ];

    public static function getLang()
    {
        $lang = config("app.locale");
        if (session()->has("language")) {
            $lang = session("language");
        }
        return $lang;
    }

    public static function setLang($language): bool
    {
        if (key_exists($language, static::$lang)) {
            session()->put("language", $language);
            return true;
        }
        return false;
    }
}
