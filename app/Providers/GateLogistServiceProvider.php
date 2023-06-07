<?php

namespace App\Providers;

//Illuminate
use App\Models\Favorites;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
//App
use App\Models\Customer;
use App\Models\User;

class GateLogistServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("logist-orders", function (User $user) {
            if ($user->role == "logist") {
                return true;
            }
            return false;
        });
        Gate::define("logist-acceptance", function (User $user) {
            if ($user->role == "logist") {
                return true;
            }
            return false;
        });
        Gate::define("logist-collected", function (User $user) {
            if ($user->role == "logist") {
                return true;
            }
            return false;
        });
        Gate::define("logist-collected-archival", function (User $user) {
            if ($user->role == "logist") {
                return true;
            }
            return false;
        });
    }
}
