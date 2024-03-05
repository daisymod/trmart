<?php


namespace App\Requests;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class TrendyolRequest    extends BaseRequest
{
    public function getData($url){
        $this->request = new Request('GET', $url, ['Content-Type' => 'html/text']);
        sleep(3);
        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'verify' => false,
                'connect_timeout' => 60

            ]);

            return $response->getBody()->getContents();
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
            if ($exception->getCode() === 429){
                Log::info(print_r($exception->getResponse()->getHeader('Retry-After')[0],true));
                if (isset($exception->getResponse()->getHeader('Retry-After')[0])){
                    sleep($exception->getResponse()->getHeader('Retry-After')[0]);
                }
            }
            return ['data' => ['error' => $exception->getMessage()], 'code' => $exception->getCode()];
        }

    }

}
