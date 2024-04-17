<?php

namespace Database\Seeders;

use App\Fields\LocationField;
use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class CitySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('countries')->truncate();
        DB::table('cities')->truncate();
        $cityIndex = 1;

        $params["v"] = "5.131";
        $params["code"] = "ru,tr,kz";
        $params['need_all'] = 1;
        //$params['lang'] = 'ru,en';
        $params["access_token"] = "f8d47cf8f8d47cf8f8d47cf8b9f8a8f89eff8d4f8d47cf89aa23aba03ca0eb00f5e3a59";
        $key = static::class . md5("database.getCountries" . http_build_query($params));
        $result = Cache::get($key, false);
        if ($result === false) {
            $url = "https://api.vk.com/method/database.getCountries?" . http_build_query($params);
            echo $url;
            $ch = curl_init(); // инициализация
            curl_setopt($ch, CURLOPT_URL, $url); // адрес страницы для скачивания
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);   //TIMEOUT
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //Переходим по редиректам
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // нам нужно вывести загруженную страницу в переменную
            $result = curl_exec($ch);
            curl_close($ch);
            Cache::put($key, $result, 60 * 60 * 24);
        }
        $countries = json_decode($result, true);
        foreach ($countries['response']['items'] as $item){
                Country::query()->create([
                    'name_ru' => $item['title'],
                    'name_en' => $item['title'],
                    'vk_id' => $item['id'],
                ]);
        }

        $params["v"] = "5.131";
        $params["code"] = "ru,tr,kz";
        $params['need_all'] = 1;
        $params['lang'] = 'ru';
        $params["access_token"] = "f8d47cf8f8d47cf8f8d47cf8b9f8a8f89eff8d4f8d47cf89aa23aba03ca0eb00f5e3a59";
        $key = static::class . md5("database.getCountries" . http_build_query($params));
        $result = Cache::get($key, false);
        if ($result === false) {
            $url = "https://api.vk.com/method/database.getCountries?" . http_build_query($params);
            echo $url;
            $ch = curl_init(); // инициализация
            curl_setopt($ch, CURLOPT_URL, $url); // адрес страницы для скачивания
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);   //TIMEOUT
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //Переходим по редиректам
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // нам нужно вывести загруженную страницу в переменную
            $result = curl_exec($ch);
            curl_close($ch);
            Cache::put($key, $result, 60 * 60 * 24);
        }
        $countries = json_decode($result, true);
        $index = 1;
        foreach ($countries['response']['items'] as $item){
            Country::query()->where('id','=',$index)
            ->update([
                'name_ru' => $item['title'],
            ]);
            $index++;
        }




        $data = Country::all();

        foreach ($data as $city_id){
            $params["v"] = "5.131";
            $params['need_all']  = 0;
            $params['lang'] = "en";
            $params['country_id'] = $city_id->vk_id;
            $params['count']  = 1000;
            $params["access_token"] = "f8d47cf8f8d47cf8f8d47cf8b9f8a8f89eff8d4f8d47cf89aa23aba03ca0eb00f5e3a59";
            $key = static::class . md5("database.getCities" . http_build_query($params));
            $result = Cache::get($key, false);
            if ($result === false) {
                $url = "https://api.vk.com/method/database.getCities?" . http_build_query($params);
                echo $url;
                $ch = curl_init(); // инициализация
                curl_setopt($ch, CURLOPT_URL, $url); // адрес страницы для скачивания
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);   //TIMEOUT
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //Переходим по редиректам
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // нам нужно вывести загруженную страницу в переменную
                $result = curl_exec($ch);
                curl_close($ch);
                Cache::put($key, $result, 60 * 60 * 24);
            }

            $cities = json_decode($result, true);
            foreach ($cities['response']['items'] as $item){
                City::query()->create([
                    'name_ru' => $item['title'],
                    'name_en' => $item['title'],
                    'country_id' => $city_id->id,
                ]);
            }


            $params["v"] = "5.131";
            $params['country_id'] = $city_id->vk_id;
            $params['lang'] = 'ru';
            $params['need_all']  = 0;
            $params['count']  = 1000;
            $params["access_token"] = "f8d47cf8f8d47cf8f8d47cf8b9f8a8f89eff8d4f8d47cf89aa23aba03ca0eb00f5e3a59";
            $key = static::class . md5("database.getCities" . http_build_query($params));
            $result = Cache::get($key, false);
            if ($result === false) {
                $url = "https://api.vk.com/method/database.getCities?" . http_build_query($params);
                echo $url;
                $ch = curl_init(); // инициализация
                curl_setopt($ch, CURLOPT_URL, $url); // адрес страницы для скачивания
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);   //TIMEOUT
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //Переходим по редиректам
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // нам нужно вывести загруженную страницу в переменную
                $result = curl_exec($ch);
                curl_close($ch);
                Cache::put($key, $result, 60 * 60 * 24);
            }

            $cities = json_decode($result, true);


            foreach ($cities['response']['items'] as $item){
                City::query()->where('id','=',$cityIndex)
                    ->update([
                        'name_ru' => $item['title'],
                    ]);
                $cityIndex++;
            }
        }

    }
}
