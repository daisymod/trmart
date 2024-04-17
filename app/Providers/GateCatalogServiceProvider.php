<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateCatalogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("catalog-list", function (User $user) {
            if (in_array($user->role, ["admin", "merchant"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("catalog-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("catalog-edit", function (User $user, Catalog $catalog) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define("catalog-del", function (User $user, Catalog $catalog) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
