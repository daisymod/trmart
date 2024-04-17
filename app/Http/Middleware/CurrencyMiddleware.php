<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;

class CurrencyMiddleware
{

    public function __construct(public CurrencyService $service)
    {
    }

    public function handle(Request $request, Closure $next)
    {

        return $next($request)
            ->withCookie(cookie()->forever('currency', $this->service->getCurrencyByCountry()));
    }
}
