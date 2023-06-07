<?php

namespace App\Providers;

use App\Models\page;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateParserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("parser-list", function (User $user) {
            if (in_array($user->role, ["admin",'merchant'])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
