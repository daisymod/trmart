<?php


namespace App\Requests;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

class ImageRequest    extends BaseRequest
{
    public function getData($url){


        $this->request = new Request('GET', $url, ['Content-Type' => 'application/json']);

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
