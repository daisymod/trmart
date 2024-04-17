<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateGeoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("country-list", function () {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("country-edit", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("country-update", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("city-edit", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("city-update", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("city-delete", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("country-update-excel-load", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
    }
}
