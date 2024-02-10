<?php


namespace App\Requests;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

class GptRequest extends BaseRequest
{
    public function getData($text,$language,$log = null){
        $post_fields = '{
              "model": "gpt-4",
                "messages": [
                    {
                        "role": "system",
                        "content": "Переведи данный текст с турецкого языка на '.$language.':"
                    },
                    {
                        "role": "user",
                        "content": "'.trim($text, " \n.").'"
                    }
                ],
                "temperature": 1,
                "max_tokens": 2048,
                "top_p": 1,    
                "frequency_penalty": 0,
                "presence_penalty": 0 
        }';

        $this->request = new Request('POST', "https://api.openai.com/v1/chat/completions",
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer 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'
            ],$post_fields);

        try {
            // отправка запроса и получение результата
            $response = $this->client->send($this->request,[
                'verify' => false,
                'connect_timeout' => 60,
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
