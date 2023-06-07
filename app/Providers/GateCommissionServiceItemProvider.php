<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateCommissionServiceItemProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("commission-list", function () {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("commission-edit", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("commission-update", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
