<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusMerchantGate
{

    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::user()->role == 'admin'){
            return $next($request);
        }

        if (Auth::user()->status == 2 && Auth::user()->role == 'merchant'){
            return $next($request);
        }else{
            return redirect(('/'));
        }
    }
}
