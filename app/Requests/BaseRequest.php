<?php


namespace App\Requests;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class BaseRequest
{
    // экземпляр клиента GuzzleHttp
    protected Client $client;
    // экземпляр запроса GuzzleHttp
    protected Request $request;

    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $this->client =  new Client();
    }

}