<?php

namespace App\Providers;

//Illuminate
use App\Models\Favorites;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
//App
use App\Models\Customer;
use App\Models\User;

class GateCustomerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("customer-list", function (User $user) {
            if (in_array($user->role, ["user"])) {
                return true;
            }
            return false;
        });
        Gate::define("customer-add", function (User $user) {
            if (in_array($user->role, ["user"])) {
                return true;
            }
            return false;
        });
        Gate::define("customer-edit", function (User $user, Customer $merchant) {
            if ($user->role == "user" or $merchant->id == Auth::user()->id) {
                return true;
            }
            return false;
        });
        Gate::define("customer-del", function (User $user) {
            if (in_array($user->role, ["user"])) {
                return true;
            }
            return false;
        });
        Gate::define("customer-del-all", function (User $user) {
            if (in_array($user->role, ["user"])) {
                return true;
            }
            return false;
        });
        Gate::define("customer-self", function (User $user) {
            if (in_array($user->role, ["user"])) {
                return true;
            }
            return false;
        });
        Gate::define("customer-favorites", function (User $user) {
            if (in_array($user->role, ["user"])) {
                return true;
            }
            return false;
        });
        Gate::define("customer-orders", function (User $user) {
            if ($user->role == "user") {
                return true;
            }
            return false;
        });
    }
}
