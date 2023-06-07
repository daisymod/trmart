<?php

namespace App\Providers;

use App\Models\CatalogCharacteristic;
use App\Models\CatalogCharacteristicItem;
use App\Models\Merchant;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateCatalogCharacteristicServiceItemProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("catalog-characteristic-item-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-characteristic-item-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-characteristic-item-edit", function (User $user, CatalogCharacteristicItem $item) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-characteristic-item-del", function (User $user, CatalogCharacteristicItem $item) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

    }
}
