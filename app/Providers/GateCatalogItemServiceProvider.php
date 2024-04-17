<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\CatalogItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateCatalogItemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::define("catalog-item-list", function (User $user) {
            if (in_array($user->role, ["admin", "merchant"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-add", function (User $user) {
            if (in_array($user->role, ["admin", "merchant"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-edit", function (User $user, CatalogItem $catalogItem) {
            if ($user->role == "admin" or $catalogItem->user_id == $user->id) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-del", function (User $user, CatalogItem $catalogItem) {
            if ($user->role == "admin" or $catalogItem->user_id == $user->id) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-verification-send", function (User $user, CatalogItem $catalogItem) {
            if ($user->role == "merchant" and
                $catalogItem->user_id == $user->id and
                $catalogItem->status <> 1 and
                $catalogItem->status <> 2
            ) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-excel-load", function (User $user) {
            if (in_array($user->role, ["admin", "merchant"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-stock-list", function (User $user) {
            if (in_array($user->role, ["admin", "merchant"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-stock-save", function (User $user) {
            if (in_array($user->role, ["admin", "merchant"])) {
                return true;
            }
            return false;
        });

        Gate::define("catalog-item-status", function (User $user, CatalogItem $catalogItem) {
            if ($user->role == "admin") {
                return true;
            }
            return false;
        });
    }
}
