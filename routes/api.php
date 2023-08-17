<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get("/test", [\App\Http\Controllers\CartController::class, "actionIndexPost"])->name("test.test");


Route::post("/catalog_item/export", [\App\Http\Controllers\CatalogItemController::class, "actionExcelExport"])->name("catalog_item.export");

Route::get("/shop/find", [\App\Http\Controllers\ShopController::class, "search"])->name("shop.search");

Route::get("/country/get", [\App\Http\Controllers\GeoController::class, "apiIndex"])->name("geo.apiindex");

Route::get("/region/get/{id}", [\App\Http\Controllers\GeoController::class, "apiRegionIndex"])->name("geo.apiRegionindex");

Route::get("/area/get/{id}", [\App\Http\Controllers\GeoController::class, "apiAreaIndex"])->name("geo.apiAreaindex");

Route::get("/city/get/{id}", [\App\Http\Controllers\GeoController::class, "apiCityIndex"])->name("geo.apiCityindex");

Route::get("/postcode/get/{id}", [\App\Http\Controllers\GeoController::class, "getPostcode"])->name("geo.apiGetPostcode");

Route::get("/currency/calculate", [\App\Http\Controllers\CurrencyController::class, "getCoefficient"])->name("currency.calculate");

Route::get("/calc-delivery", [\App\Http\Controllers\CalculateController::class, "getCalculateDelivery"])->name("calculate.delivery");


Route::get('/change-pass', function () {
  \Illuminate\Support\Facades\Artisan::call('db:seed --class=UpdatePassword');

})->name("change");

Route::get('/redirect', function () {
    //return redirect(route('catalog_item.list'));
})->name("redirect");

Route::get("/product/paginate", [\App\Http\Controllers\CatalogItemController::class, "getPaginatePage"])->name("product.paginate");


Route::get("/product/color-size", [\App\Http\Controllers\ShopController::class, "getColorAndSizeById"])->name("getColorAndSizeById");


Route::post("/test", [\App\Http\Controllers\TestController::class, "index"])->name("TestController");
