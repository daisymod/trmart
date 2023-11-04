<?php

namespace App\Http\Controllers;

use App\Jobs\TestLoadChainJob;
use App\Models\User;
use App\Services\CatalogItemsExcelLoadServiceTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index(Request $request){
        $ch = curl_init();

        $url = 'https://api.openai.com/v1/completions';

        $api_key = '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';

        $post_fields = '{
             "model": "gpt-3.5-turbo",
              "messages": [
                {
                  "role": "system",
                  "content": "You are a helpful assistant."
                },
                {
                  "role": "user",
                  "content": "Who won the world series in 2020?"
                },
                {
                  "role": "assistant",
                  "content": "The Los Angeles Dodgers won the World Series in 2020."
                },
                {
                  "role": "user",
                  "content": "Where was it played?"
                }
              ]
            }';

        $header  = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $response = json_decode($result);
        var_dump($response->choices[0]->text);
    }
}
