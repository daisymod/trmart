<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            //Cache::put('user-is-online-' . Auth::user()->id, true, now()->addMinutes(2));
            User::where("id", Auth::user()->id)->update(['last_seen' => now()]);
        }
        return $next($request);
    }
}
