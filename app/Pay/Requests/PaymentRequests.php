<?php


namespace App\Pay\Requests;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class PaymentRequests extends BaseRequest
{
    public function authToken(){
        $this->request = new Request('GET', "https://api.test.isbank.com.tr/api/sandbox-isbank/v1/jwt/");

        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'verify' => false,
                'headers' => [
                    'X-Isbank-Client-Id' => "b7c72d7c7f64873381f3ac3d5614eb66",
                    'X-Isbank-Client-Secret' => '44a541248552ed711d4a0044055f8a18',
                    'accept' => 'application/json'
                ],
                'connect_timeout' => 60
            ]);

            return ['data' => json_decode($response->getBody()->getContents(), true), 'code' => 200];
        }
        catch (ServerException| ClientException $exception)
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

    public function makePayment($token,$body){

        $headers =  [   'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-IBM-client-id' => 'b7c72d7c7f64873381f3ac3d5614eb66',
            'X-IBM-client-secret' => '44a541248552ed711d4a0044055f8a18',
            'Authorization: Bearer'."AAIgYTEwNWMyZGEyNGJiMTNiNzdhNzA2MzNiNDU1OGM4ZmXsHNO8xaRfc020UQqwA5sa82OHtNIUx5u_gVFGqDOdSeeFymbGiVt8xet4MWHoep9-1_6J_vrrYC8bc3mTHlSJe7r7XY60iCF_Cbg2p4VA7r9wH4J6M5IUfxTEFQv0jX8",
        ];

        $this->request = new Request('POST', "https://api.test.isbank.com.tr/api/isbank/maximum/paybylink/v1/payment-links",
            $headers
           );

        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'json' => [
                    'language_code' => 'ru',
                    'branch_no' => 1,
                    'personal_description' => 'Заказ #',
                    "amount" => $body->price ?? 100,
                    "merchant_no" => Auth::user()->id ?? 1,
                    "installment" => 1,
                    "currency_code" => "KZT",
                    "is_domestic" => true
                ],
                'verify'=>false,
                'connect_timeout' => 60
            ]);

            return ['data' => json_decode($response->getBody()->getContents(), true), 'code' => 200];
        }
        catch (ServerException| ClientException $exception)
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
