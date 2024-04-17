<?php

namespace App\Fields;

use App\GetUrl;
use Illuminate\Support\Facades\Cache;

class LocationField extends BasicField
{
    protected function standart($data, $action)
    {
        $value = (object)[
            "country" => (object)[
                "id" => $data["country_id"] ?? "1",
                "title" => $data["country_title"] ?? __("system.i8"),
            ],
            "city" => (object)[
                "id" => $data["city_id"] ?? "0",
                "title" => $data["city_title"] ?? __("system.i7"),
            ],
        ];

        $lang = app()->getLocale() == 'ru' ? 'ru' : 'en';

        try {
            $countries = json_decode($this->getVKData("database.getCountries", ["need_all" => 1, "count" => 1000,'lang' => $lang, 'code' =>"'tr'"]))->response;
        }catch (\Exception $e){
            $countries = json_decode('
		    {
	"items": [
	{
		"id": "200",
		"title": "Турция"
	},
	{
		"id": "4",
		"title": "Казахстан"
	},
	{
		"id": "1",
		"title": "Россия"
	}]
}');
        }

        $class = $this;
        return view("fields." . class_basename($this), compact("action", "value", "countries", "class"))->toHtml();
    }

    public function save($data)
    {
        $keys = [
            "country_id",
            "country_title",
            "city_id",
            "city_title"
        ];
        $fields = [];
        foreach ($keys as $k => $i) {
            if (array_key_exists($i, $data)) {
                $fields[$i] = trim($data[$i]);
            }
        }
        return $fields;
    }

    public function show($records)
    {
        return $records;
    }

    public static function getVKData($method, $params = [])
    {
        $params["v"] = "5.81";

        $params["access_token"] = env("VK_ACCESS_TOKEN");
        $key = static::class . md5($method . http_build_query($params));
        $result = Cache::get($key, false);
        if ($result === false) {
            $url = "https://api.vk.com/method/$method?" . http_build_query($params);
            $ch = curl_init(); // инициализация
            curl_setopt($ch, CURLOPT_URL, $url); // адрес страницы для скачивания
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);   //TIMEOUT
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //Переходим по редиректам
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // нам нужно вывести загруженную страницу в переменную
            $result = curl_exec($ch);
            curl_close($ch);
            Cache::put($key, $result, 60 * 60 * 24);
        }
        return $result;
    }

}
