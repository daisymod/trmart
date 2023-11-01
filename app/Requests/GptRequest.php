<?php


namespace App\Requests;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

class GptRequest extends BaseRequest
{
    public function getData($text,$language){

        $this->request = new Request('GET', "https://api.openai.com/v1/chat/completions",
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer: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'
            ]);

        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'verify' => false,
                'connect_timeout' => 60,
                'json' => [
                    'SYSTEM' => 'You will be provided with a sentence in English, and your task is to translate it into '.$language.'.',
                    'USER'  => $text,
                ]
            ]);

            return ['data' => json_decode($response->getBody()->getContents(), true), 'code' => 200];

        }
        catch (ServerException | ClientException $exception)
        {
            if ($exception->getCode() === 500){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 500];
            }
            if ($exception->getCode() === 400){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 400];
            }
            if ($exception->getCode() === 404){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 404];
            }
            if ($exception->getCode() === 422){
                return ['data' => ['error' => 'Такого записи нет'], 'code' => 422];
            }
            return ['data' => ['error' => $exception->getMessage()], 'code' => $exception->getCode()];
        }

    }



    public function getDataProduct($url){
        $this->request = new Request('GET', "https://api.ozdilekteyim.com/rest/v2/magaza-magaza-store/products/".$url.'?fields=name,description,discountRate,images,customUrl,brand,purchasable,baseOptions(DEFAULT),baseProduct,variantOptions(FULL),variantType&lang=tr&curr=TRY', ['Content-Type' => 'application/json']);

        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'verify' => false,
                'connect_timeout' => 60

            ]);

            return ['data' => json_decode($response->getBody()->getContents(), true), 'code' => 200];

        }
        catch (ServerException | ClientException $exception)
        {
            if ($exception->getCode() === 500){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 500];
            }
            if ($exception->getCode() === 400){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 400];
            }
            if ($exception->getCode() === 404){
                return ['data' => ['error' => $exception->getMessage()], 'code' => 404];
            }
            if ($exception->getCode() === 422){
                return ['data' => ['error' => 'Такого записи нет'], 'code' => 422];
            }
            return ['data' => ['error' => $exception->getMessage()], 'code' => $exception->getCode()];
        }
    }
}
