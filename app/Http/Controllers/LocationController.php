<?php

namespace App\Http\Controllers;

use App\Fields\LocationField;

class LocationController extends Controller
{
    public function actionCity()
    {
        $records = json_decode(LocationField::getVKData("database.getCities", [
            "country_id" => request("countryId"),
            "q" => request("query")
        ]), true)["response"]["items"];

        $records = array_map(function ($record) {
            if (!empty($record["region"])) {
                $record["value"] = "{$record["title"]}, {$record["region"]}";
                $record["region"] = $record["region"];
            } else {
                $record["value"] = $record["title"];
            }
            return $record;
        }, $records);
        return ["suggestions" => $records];
    }
}
