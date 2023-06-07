<?php

namespace App\Providers;

use App\Models\ShippingRate;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateShippingRateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("shipping-rate-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("shipping-rate-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("shipping-rate-edit", function (User $user, ShippingRate $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("shipping-rate-del", function (User $user, ShippingRate $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
