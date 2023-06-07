<?php

namespace App\Services;

use App\Jobs\SendMailJob;

class SendMailService
{
    protected static string $notificationEmail = "nbaskoff@gmail.com";

    public static function sendMail($email, $title, $body)
    {
        SendMailJob::dispatch($email, $title, $body);
    }

    public static function sendNotification($title, $body)
    {
        static::sendMail(static::$notificationEmail, $title, $body);
    }


}
