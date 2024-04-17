<?php

namespace App\Providers;

use App\Models\page;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GatePageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("page-list", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("page-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("page-edit", function (User $user, page $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("page-del", function (User $user, page $record) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
