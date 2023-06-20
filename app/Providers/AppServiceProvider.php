<?php

namespace App\Providers;

use App\Models\Catalog;
use App\Models\CatalogItem;
use App\Models\User;
use App\Services\CartService;
use App\Services\CityService;
use App\Services\CountryService;
use App\Services\LanguageService;
use App\Services\LocationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        if (request()->getHost() ==  "turkiyemart.com") {
            URL::forceScheme("https");
        }

        Carbon::setLocale(app()->getLocale());

        view()->share("location", ['country_id'=> 2, 'city_id' => 2]);
        view()->share("langList", LanguageService::$lang);
        view()->share("catalogMenu", Catalog::query()->where("parent_id", 0)->orderBy("name_ru")->get());
        view()->composer("*", function ($view) {
            $merchantStep = 1;
            if (Auth::hasUser()) {
                if (Auth::user()->status == 2) {
                    $merchantStep = 2;
                }
                if (CatalogItem::query()->where("user_id", Auth::id())->count() > 0) {
                    $merchantStep = 3;
                }
                if (CatalogItem::query()->where("user_id", Auth::id())->where("stock", ">", 0)->count() > 0) {
                    $merchantStep = 4;
                }
            }
            $view->with("merchantStep", $merchantStep);
            $view->with("lang", LanguageService::getLang());
            $view->with("cart", CartService::getCart());

        });
    }
}
