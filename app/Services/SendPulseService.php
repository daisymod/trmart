<?php

namespace App\Services;

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

class SendPulseService
{
    public static function sendSMS($phone, $text)
    {
        $SPApiClient = new ApiClient(env("SENDPULSE_USER_ID"), env("SENDPULSE_SECRET"), new FileStorage());
        return $SPApiClient->sendSmsByList([$phone], [
            "sender" => "turkiyemart",
            "transliterate" => 0,
            "body" => $text
        ], []);
    }
}
