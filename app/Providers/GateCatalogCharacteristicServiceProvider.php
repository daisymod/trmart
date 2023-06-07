<?php

namespace App\Providers;

use App\Models\CatalogCharacteristic;
use App\Models\Merchant;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateCatalogCharacteristicServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("catalog-characteristic-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-characteristic-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-characteristic-edit", function (User $user, CatalogCharacteristic $catalogCharacteristic) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-characteristic-del", function (User $user, CatalogCharacteristic $catalogCharacteristic) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

    }
}
