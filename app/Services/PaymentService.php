<?php

namespace App\Services;

use App\Pay\Requests\PaymentRequests;
use Doctrine\DBAL\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class PaymentService
{
    public function makePayment(Request $request){
        $payment = new PaymentRequests();
        $token = $payment->authToken();
        var_dump($token);

    }


    public function newAuth(){



    }


}
