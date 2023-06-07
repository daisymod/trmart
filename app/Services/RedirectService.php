<?php

namespace App\Services;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class RedirectService
{

    public function redirect($routeName){

        return Redirect::to(route($routeName),308);
    }

}
