<?php

namespace App\Providers;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateMerchantServiceProvider extends ServiceProvider
{
    public function boot()
    {

        Gate::define("show-merchant", function (User $user) {
            if (in_array($user->role, ["admin","merchant"])) {
                return true;
            }
            return false;
        });

        Gate::define("merchant-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });
        Gate::define("merchant-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });
        Gate::define("merchant-edit", function (User $user, Merchant $merchant) {
            if (in_array($user->role, ["admin"]) or $merchant->id == Auth::user()->id) {
                return true;
            }
            return false;
        });
        Gate::define("merchant-del", function (User $user, Merchant $merchant) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });
        Gate::define("merchant-self", function (User $user) {
            if ($user->role == "merchant") {
                return true;
            }
            return false;
        });
    }
}
