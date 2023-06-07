<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateDeliveryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("delivery-index", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });
        Gate::define("delivery-save", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });
    }
}
