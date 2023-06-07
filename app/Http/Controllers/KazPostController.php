<?php

namespace App\Http\Controllers;

use App\Clients\KazPost;
use App\Enums\KazPost\Enums;

class KazPostController extends Controller
{
    public function test(): string|object
    {
        $client = new KazPost();
        return $client->getAddrLetter();
    }

    public function city(): string|object
    {
        $client = new KazPost();
        return $client->searchCity();
    }

    public function enums(): string
    {
        $enum = new Enums();
        return $enum->getMailCtg(3);
    }

    public function calc(): string|object
    {
        $client = new KazPost();
        return $client->getPostRate('750', '1000', '010000');
    }
}
