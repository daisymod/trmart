<?php

namespace App\Providers;

use App\Models\ShippingMethod;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateShippingMethodServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("shipping-method-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("shipping-method-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("shipping-method-edit", function (User $user, ShippingMethod $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("shipping-method-del", function (User $user, ShippingMethod $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
