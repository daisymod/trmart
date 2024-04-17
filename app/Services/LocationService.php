<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class LocationService
{
    public static function getLocation(){
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ip = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = 'UNKNOWN';

        if ($ip == 'UNKNOWN'){
            return
                [
                    'country_id' => 1,
                    'city_id' => 1
                ];
        }else{
            $data = \Location::get($ip);

            $country = Country::where('name_en','=',$data->countryName ?? 1)
                ->first();

            $city = City::where('name_en','=',$data->cityName ?? 1)
                ->first();

            return
                [
                    'country_id' => $country->id ?? 1,
                    'city_id' => $city->id ?? 1
                ];
        }

    }


}
