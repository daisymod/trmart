<?php

namespace App\Providers;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateAdminServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("admin-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("admin-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("admin-edit", function (User $user, User $admin) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });

        Gate::define("admin-del", function (User $user, User $admin) {
            if (in_array($user->role, ["admin"])) {
                return true;
            }
            return false;
        });
    }
}
