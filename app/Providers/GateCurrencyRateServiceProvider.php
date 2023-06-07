<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\CatalogItem;
use App\Models\CurrencyRate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateCurrencyRateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("currency-rate-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("currency-rate-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("currency-rate-edit", function (User $user, CurrencyRate $currencyRate) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("currency-rate-del", function (User $user, CurrencyRate $currencyRate) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

    }
}
