<?php

namespace App\Services;

class SMSTrafficService
{
    public static function sendSMS($phone, $text)
    {
        $params = [
            "login" => "gcssm",
            "password" => "HyIv2VsJ",
            "phones" => $phone,
            "message" => $text
        ];
        $url = "https://api.smstraffic.ru/multi.php?" . http_build_query($params);
        $ch = curl_init(); // инициализация
        curl_setopt($ch, CURLOPT_URL, $url); // адрес страницы для скачивания
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);   //TIMEOUT
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //Переходим по редиректам
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // нам нужно вывести загруженную страницу в переменную
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
