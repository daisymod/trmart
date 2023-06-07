<?php

namespace App\Providers;

use App\Models\Merchant;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateNewsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("news-list", function (User $user) {
            if (in_array($user->role, ["admin", "merchant"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("news-add", function (User $user) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("news-edit", function (User $user, News $news) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });
        Gate::define("news-del", function (User $user, News $news) {
            if (in_array($user->role, ["admin"])) {
                return true;
            } else {
                return false;
            }
        });

    }
}
