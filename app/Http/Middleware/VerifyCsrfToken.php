<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/callback'
    ];



    public function handle($request, Closure $next)
    {
        if($request->route()->named('callback')) {
            if (!Auth::check() || Auth::guard()->viaRemember()) {
                $this->except[] = route('callback');
            }
        }

        return parent::handle($request, $next);
    }
}
