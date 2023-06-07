<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateBrandsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("brand-list", function () {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("brand-edit", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("brand-update", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("brand-delete", function (User $user) {
            if (in_array(Auth::user()->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
