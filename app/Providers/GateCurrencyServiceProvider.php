<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateCurrencyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("currency-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("currency-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("currency-edit", function (User $user, Currency $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("currency-del", function (User $user, Currency $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
