<?php

namespace App\Providers;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateUserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("user-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("user-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("user-edit", function (User $user, User $merchant) {
            if (in_array($user->role, ["admin"]) or $merchant->id == Auth::user()->id) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("user-del", function (User $user, User $merchant) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
