<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get("/", [\App\Http\Controllers\IndexController::class, "actionIndex"])->name("index");

Route::post("/", [\App\Http\Controllers\ContactController::class, "sendMessage"])->name("sendMessage");

Route::group(["prefix" => "user"], function () {
    Route::post("/login", [\App\Http\Controllers\UserController::class, "actionLoginPost"])->name("user.login");
    Route::post("/reg", [\App\Http\Controllers\UserController::class, "actionRegPost"])->name("user.reg");
    Route::post("/reg/sms", [\App\Http\Controllers\UserController::class, "actionRegSMSPost"])->name("user.reg.sms");
    Route::get("/exit", [\App\Http\Controllers\UserController::class, "actionExit"])->name("user.exit");
});

Route::group(["prefix" => "merchant"], function () {
    //Route::get("/reg", [\App\Http\Controllers\MerchantController::class, "actionRegGet"])->name("merchant.reg");
    Route::post("/reg", [\App\Http\Controllers\MerchantController::class, "actionRegPost"])->name("merchant.reg");
    //Route::get("/reg/sms", [\App\Http\Controllers\MerchantController::class, "actionRegSMSGet"])->name("merchant.reg.sms");
    Route::post("/reg/sms", [\App\Http\Controllers\MerchantController::class, "actionRegSMSPost"])->name("merchant.reg.sms");
});


Route::post("/send-code", [\App\Http\Controllers\UserController::class, "resetPasswordCode"])->name("user.checkPhone");
Route::post("/check-code", [\App\Http\Controllers\UserController::class, "checkPasswordCode"])->name("user.checkSMS");
Route::post("/reset-password", [\App\Http\Controllers\UserController::class, "resetPassword"])->name("user.reset-password");



Route::group(["middleware" => ["auth"]], function () {
    Route::group(["middleware" => ["verify-merchant"]], function () {
        Route::get("/catalog_item/list", [\App\Http\Controllers\CatalogItemController::class, "actionListGet"])->name("catalog_item.list");
        Route::post("/catalog_item/list", [\App\Http\Controllers\CatalogItemController::class, "actionListPost"]);
        Route::get("/catalog_item/add", [\App\Http\Controllers\CatalogItemController::class, "actionAddGet"])->name("catalog_item.add");
        Route::post("/catalog_item/add", [\App\Http\Controllers\CatalogItemController::class, "actionAddPost"]);
        Route::get("/catalog_item/edit/{id}", [\App\Http\Controllers\CatalogItemController::class, "actionEditGet"])->name("catalog_item.edit");
        Route::post("/catalog_item/edit/{id}", [\App\Http\Controllers\CatalogItemController::class, "actionEditPost"]);
        Route::get("/catalog_item/del/{id}", [\App\Http\Controllers\CatalogItemController::class, "actionDel"])->name("catalog_item.del");
        Route::get("/catalog_item/verification/send/{id}", [\App\Http\Controllers\CatalogItemController::class, "actionVerificationSend"])->name("catalog_item.verification.send");

        Route::get("/merchant/orders", [\App\Http\Controllers\MerchantController::class, "getOrder"])->name("merchant.orders");
        Route::get("/merchant/pays", [\App\Http\Controllers\MerchantController::class, "getPays"])->name("merchant.getPays");

        Route::post("/merchant/pays/export", [\App\Http\Controllers\MerchantController::class, "exportOrders"])->name("merchant.exportOrders");

        Route::get("/merchant/orders/cancel/{id}", [\App\Http\Controllers\MerchantController::class, "cancelOrder"])->name("merchant.cancel");
        Route::post("/merchant/orders/onway/{id}", [\App\Http\Controllers\MerchantController::class, "setStatusOnWay"])->name("merchant.onWay");

        Route::get("/catalog_item/stock", [\App\Http\Controllers\CatalogItemController::class, "actionStockList"])->name("catalog_item.stock.list");
        Route::post("/catalog_item/stock", [\App\Http\Controllers\CatalogItemController::class, "actionStockSave"]);

        Route::group(["prefix" => "user-cart"], function () {
            Route::get("/list", [\App\Http\Controllers\UserCartController::class, "index"])->name("userCart.list");
            Route::get("/{id}", [\App\Http\Controllers\UserCartController::class, "show"])->name("userCart.edit");
            Route::post("/{id}", [\App\Http\Controllers\UserCartController::class, "update"])->name("userCart.update");
        });
    });




    Route::post("/catalog_item/load", [\App\Http\Controllers\CatalogItemController::class, "actionExcelLoad"])->name("catalog_item.excel_load");

    Route::post("/set-lang", function () {
        User::where('id','=',Auth::user()->id)
            ->update(
                [
                    'lang' => app()->getLocale()
                ]
            );
    });


    Route::get("/customer/self", [\App\Http\Controllers\CustomerController::class, "actionSelfGet"])->name("customer.self");
    Route::get("/customer/favorites", [\App\Http\Controllers\CustomerController::class, "actionFavoritesGet"])->name("customer.favorites");
    Route::get("/customer/orders", [\App\Http\Controllers\CustomerController::class, "actionOrdersGet"])->name("customer.orders");
    Route::get("/customer/order/{id}", [\App\Http\Controllers\CustomerController::class, "actionOrderItemGet"])->name("customer.order");
    Route::post("/customer/self", [\App\Http\Controllers\CustomerController::class, "actionSelfPost"]);
    Route::get("/customer/del/{id}", [\App\Http\Controllers\CustomerController::class, "actionDeleteFavoritesItem"])->name("customer.del");
    Route::get("/customer/del/all/{userId}", [\App\Http\Controllers\CustomerController::class, "actionDeleteFavoritesAll"])->name("customer.delAll");
    Route::get("/customer/canceled/{id}", [\App\Http\Controllers\CustomerController::class, "actionCanceledOrder"])->name("customer.canceled");
    Route::get("/customer/review", [\App\Http\Controllers\CustomerController::class, "actionReview"])->name("customer.review");

    Route::get("/logist/orders", [\App\Http\Controllers\LogistController::class, "actionOrdersGet"])->name("logist.orders");
    Route::get("/logist/acceptance", [\App\Http\Controllers\LogistController::class, "actionAcceptanceGet"])->name("logist.acceptance");
    Route::get("/logist/collected", [\App\Http\Controllers\LogistController::class, "actionCollectedGet"])->name("logist.collected");
    Route::get("/logist/orders_all", [\App\Http\Controllers\LogistController::class, "getOrders"])->name("logist.all_orders");
    Route::get("/logist/collected/item/{id}", [\App\Http\Controllers\LogistController::class, "actionCollectedItemGet"])->name("logist.collected.item");
    Route::get("/logist/collected-archival", [\App\Http\Controllers\LogistController::class, "actionCollectedArchivalGet"])->name("logist.archival.collected");
    Route::get("/logist/collected-archival/item/{id}", [\App\Http\Controllers\LogistController::class, "actionCollectedArchivalItemGet"])->name("logist.archival.collected.item");
    Route::get("/logist/to-collect", [\App\Http\Controllers\LogistController::class, "actionToCollect"])->name("logist.to.collect");
    Route::get("/logist/to-orders", [\App\Http\Controllers\LogistController::class, "actionToOrders"])->name("logist.to.orders");
    Route::get("/logist/item/export/{id}", [\App\Http\Controllers\LogistController::class, "actionCollectedItemExport"])->name("logist.export.item");
    Route::get("/logist/item/delete/{id}/{form}", [\App\Http\Controllers\LogistController::class, "actionCollectedItemDelete"])->name("logist.delete.item");
    Route::get("/logist/item/delete/{id}", [\App\Http\Controllers\LogistController::class, "actionDownloadPdf"])->name("logist.download.pdf");
    Route::get("/logist/item/letter/{id}", [\App\Http\Controllers\LogistController::class, "actionAddLetter"])->name("logist.get.letter");
    Route::get("/logist/item/add", [\App\Http\Controllers\LogistController::class, "actionCollectedItemAdd"])->name("logist.add.item");
    Route::get("/logist/item/status/{id}", [\App\Http\Controllers\LogistController::class, "actionCollectedUpdateStatus"])->name("logist.update.status.item");

    Route::get("/merchant/list", [\App\Http\Controllers\MerchantController::class, "actionList"])->name("merchant.list");
    Route::post("/merchant/list", [\App\Http\Controllers\MerchantController::class, "actionDND"]);
    //Route::get("/merchant/add", [\App\Http\Controllers\MerchantController::class, "actionEdit"])->name("merchant.add");
    Route::get("/merchant/edit/{id}", [\App\Http\Controllers\MerchantController::class, "actionEditGet"])->name("merchant.edit");
    Route::post("/merchant/edit/{id}", [\App\Http\Controllers\MerchantController::class, "actionEditPost"]);
    Route::get("/merchant/del/{id}", [\App\Http\Controllers\MerchantController::class, "actionDel"])->name("merchant.del");
    Route::get("/merchant/self", [\App\Http\Controllers\MerchantController::class, "actionSelfGet"])->name("merchant.self");
    Route::post("/merchant/self", [\App\Http\Controllers\MerchantController::class, "actionSelfPost"]);
    Route::get("/merchant/order/{id}", [\App\Http\Controllers\MerchantController::class, "getOrderPage"])->name("merchant.order");




    Route::get("/user/lk", [\App\Http\Controllers\UserController::class, "actionLK"])->name("user.lk");
    Route::get("/user/list", [\App\Http\Controllers\UserController::class, "actionList"])->name("user.list");
    Route::get("/user/add", [\App\Http\Controllers\UserController::class, "actionEdit"])->name("user.add");
    Route::get("/user/edit/{id}", [\App\Http\Controllers\UserController::class, "actionEditGet"])->name("user.edit");
    Route::post("/user/edit/{id}", [\App\Http\Controllers\UserController::class, "actionEditPost"]);
    Route::get("/user/del/{id}", [\App\Http\Controllers\UserController::class, "actionDel"])->name("user.del");

    Route::post("/system/image/load", [\App\Http\Controllers\ImageController::class, "load"]);
    Route::post("/system/image/size", [\App\Http\Controllers\ImageController::class, "size"]);

    Route::post("/api/location/city", [\App\Http\Controllers\LocationController::class, "actionCity"]);


    Route::get("/slider/list", [\App\Http\Controllers\SliderController::class, "actionList"])->name("slider.list");
    Route::get("/slider/add", [\App\Http\Controllers\SliderController::class, "actionAddGet"])->name("slider.add");
    Route::post("/slider/add", [\App\Http\Controllers\SliderController::class, "actionAddPost"]);
    Route::get("/slider/edit/{id}", [\App\Http\Controllers\SliderController::class, "actionEditGet"])->name("slider.edit");
    Route::post("/slider/edit/{id}", [\App\Http\Controllers\SliderController::class, "actionEditPost"]);
    Route::get("/slider/del/{id}", [\App\Http\Controllers\SliderController::class, "actionDel"])->name("slider.del");

/*
    Route::get("/news/list", [\App\Http\Controllers\NewsController::class, "actionList"])->name("news.list");
    Route::get("/news/add", [\App\Http\Controllers\NewsController::class, "actionAddGet"])->name("news.add");
    Route::post("/news/add", [\App\Http\Controllers\NewsController::class, "actionAddPost"]);
    Route::get("/news/edit/{id}", [\App\Http\Controllers\NewsController::class, "actionEditGet"])->name("news.edit");
    Route::post("/news/edit/{id}", [\App\Http\Controllers\NewsController::class, "actionEditPost"]);
    Route::get("/news/del/{id}", [\App\Http\Controllers\NewsController::class, "actionDel"])->name("news.del");
*/
    Route::get("/catalog/list/{id}", [\App\Http\Controllers\CatalogController::class, "actionList"])->name("catalog.list");
    Route::get("/catalog/add/{id}", [\App\Http\Controllers\CatalogController::class, "actionAddGet"])->name("catalog.add");
    Route::post("/catalog/add/{id}", [\App\Http\Controllers\CatalogController::class, "actionAddPost"]);
    Route::get("/catalog/edit/{id}", [\App\Http\Controllers\CatalogController::class, "actionEditGet"])->name("catalog.edit");
    Route::post("/catalog/edit/{id}", [\App\Http\Controllers\CatalogController::class, "actionEditPost"]);
    Route::get("/catalog/del/{id}", [\App\Http\Controllers\CatalogController::class, "actionDel"])->name("catalog.del");

    Route::group(["prefix" => "commission"], function () {
        Route::get("/list", [\App\Http\Controllers\CommissionController::class, "index"])->name("commission.list");
        Route::get("/list/{id}", [\App\Http\Controllers\CommissionController::class, "show"])->name("commission.edit");
        Route::post("/list/{id}", [\App\Http\Controllers\CommissionController::class, "update"])->name("commission.update");
    });


    Route::group(["prefix" => "geo"], function () {
        Route::get("/list", [\App\Http\Controllers\GeoController::class, "index"])->name("geo.list");
        Route::get("/create", [\App\Http\Controllers\GeoController::class, "create"])->name("geo.create");
        Route::post("/create", [\App\Http\Controllers\GeoController::class, "store"])->name("geo.store");
        Route::get("/list/{id}", [\App\Http\Controllers\GeoController::class, "show"])->name("geo.edit");
        Route::post("/list/{id}", [\App\Http\Controllers\GeoController::class, "update"])->name("geo.update");
        Route::group(["prefix" => "city"], function () {
            Route::get("/create/{country_id}", [\App\Http\Controllers\GeoController::class, "cityCreate"])->name("city.create");
            Route::post("/create/{country_id}", [\App\Http\Controllers\GeoController::class, "cityStore"])->name("city.store");
            Route::get("/{id}", [\App\Http\Controllers\GeoController::class, "cityShow"])->name("city.edit");
            Route::post("/{id}", [\App\Http\Controllers\GeoController::class, "cityUpdate"])->name("city.update");

            Route::delete("/{id}", [\App\Http\Controllers\GeoController::class, "cityDelete"])->name("city.delete");
        });
        Route::post("/import", [\App\Http\Controllers\GeoController::class, "actionExcelLoad"])->name("geo.excel_load");
        Route::post("/import-tr", [\App\Http\Controllers\GeoController::class, "actionTrExcelLoad"])->name("geo.tr_excel_load");
    });

    Route::group(["prefix" => "brand"], function () {
        Route::get("/list", [\App\Http\Controllers\BrandController::class, "index"])->name("brand.list");
        Route::get("/create", [\App\Http\Controllers\BrandController::class, "create"])->name("brand.create");
        Route::post("/create", [\App\Http\Controllers\BrandController::class, "store"])->name("brand.store");
        Route::get("/{id}", [\App\Http\Controllers\BrandController::class, "show"])->name("brand.edit");
        Route::post("/{id}", [\App\Http\Controllers\BrandController::class, "update"])->name("brand.update");

        Route::delete("/{id}", [\App\Http\Controllers\BrandController::class, "delete"])->name("brand.delete");
    });





    Route::group(["prefix" => "color"], function () {
        Route::get("/list", [\App\Http\Controllers\ColorController::class, "index"])->name("color.list");
        Route::get("/create", [\App\Http\Controllers\ColorController::class, "create"])->name("color.create");
        Route::post("/create", [\App\Http\Controllers\ColorController::class, "store"])->name("color.store");
        Route::get("/{id}", [\App\Http\Controllers\ColorController::class, "show"])->name("color.edit");
        Route::post("/{id}", [\App\Http\Controllers\ColorController::class, "update"])->name("color.update");

        Route::delete("/{id}", [\App\Http\Controllers\ColorController::class, "delete"])->name("color.delete");
    });


    Route::group(["prefix" => "parser"], function () {
        Route::get("/list", [\App\Http\Controllers\ParserController::class, "index"])->name("parser.list");
    });







    /*
    Route::get("/shipping_method/list", [\App\Http\Controllers\ShippingMethodController::class, "actionList"])->name("shipping_method.list");
    Route::get("/shipping_method/add", [\App\Http\Controllers\ShippingMethodController::class, "actionAddGet"])->name("shipping_method.add");
    Route::post("/shipping_method/add", [\App\Http\Controllers\ShippingMethodController::class, "actionAddPost"]);
    Route::get("/shipping_method/edit/{id}", [\App\Http\Controllers\ShippingMethodController::class, "actionEditGet"])->name("shipping_method.edit");
    Route::post("/shipping_method/edit/{id}", [\App\Http\Controllers\ShippingMethodController::class, "actionEditPost"]);
    Route::get("/shipping_method/del/{id}", [\App\Http\Controllers\ShippingMethodController::class, "actionDel"])->name("shipping_method.del");
*/
    Route::get("/shipping_rate/list/{id}", [\App\Http\Controllers\ShippingRateController::class, "actionList"])->name("shipping_rate.list");
    Route::get("/shipping_rate/add/{id}", [\App\Http\Controllers\ShippingRateController::class, "actionAddGet"])->name("shipping_rate.add");
    Route::post("/shipping_rate/add/{id}", [\App\Http\Controllers\ShippingRateController::class, "actionAddPost"]);
    Route::get("/shipping_rate/edit/{id}", [\App\Http\Controllers\ShippingRateController::class, "actionEditGet"])->name("shipping_rate.edit");
    Route::post("/shipping_rate/edit/{id}", [\App\Http\Controllers\ShippingRateController::class, "actionEditPost"]);
    Route::get("/shipping_rate/del/{id}", [\App\Http\Controllers\ShippingRateController::class, "actionDel"])->name("shipping_rate.del");

    /*
        Route::get("/page/list", [\App\Http\Controllers\PageController::class, "actionList"])->name("page.list");
        Route::get("/page/add", [\App\Http\Controllers\PageController::class, "actionAddGet"])->name("page.add");
        Route::post("/page/add", [\App\Http\Controllers\PageController::class, "actionAddPost"]);
        Route::get("/page/edit/{id}", [\App\Http\Controllers\PageController::class, "actionEditGet"])->name("page.edit");
        Route::post("/page/edit/{id}", [\App\Http\Controllers\PageController::class, "actionEditPost"]);
        Route::get("/page/del/{id}", [\App\Http\Controllers\PageController::class, "actionDel"])->name("page.del");
        Route::get("/page/{url}", [\App\Http\Controllers\PageController::class, "actionUrl"])->name("page.url");
    */


    Route::get("/currency/list", [\App\Http\Controllers\CurrencyController::class, "actionList"])->name("currency.list");
    Route::get("/currency/add", [\App\Http\Controllers\CurrencyController::class, "actionAddGet"])->name("currency.add");
    Route::post("/currency/add", [\App\Http\Controllers\CurrencyController::class, "actionAddPost"]);
    Route::get("/currency/edit/{id}", [\App\Http\Controllers\CurrencyController::class, "actionEditGet"])->name("currency.edit");
    Route::post("/currency/edit/{id}", [\App\Http\Controllers\CurrencyController::class, "actionEditPost"]);
    Route::get("/currency/del/{id}", [\App\Http\Controllers\CurrencyController::class, "actionDel"])->name("currency.del");


    Route::get("/delivery/index", [\App\Http\Controllers\DeliveryController::class, "actionDelivery"])->name("delivery.index");
    Route::get("/delivery/save", [\App\Http\Controllers\DeliveryController::class, "actionPostDelivery"])->name("delivery.save");

    Route::get("/catalog_characteristic/list", [\App\Http\Controllers\CatalogCharacteristicController::class, "actionList"])->name("catalog_characteristic.list");
    Route::post("/catalog_characteristic/list", [\App\Http\Controllers\CatalogCharacteristicController::class, "actionDND"]);
    Route::get("/catalog_characteristic/add", [\App\Http\Controllers\CatalogCharacteristicController::class, "actionAddGet"])->name("catalog_characteristic.add");
    Route::post("/catalog_characteristic/add", [\App\Http\Controllers\CatalogCharacteristicController::class, "actionAddPost"]);
    Route::get("/catalog_characteristic/edit/{id}", [\App\Http\Controllers\CatalogCharacteristicController::class, "actionEditGet"])->name("catalog_characteristic.edit");
    Route::post("/catalog_characteristic/edit/{id}", [\App\Http\Controllers\CatalogCharacteristicController::class, "actionEditPost"]);
    Route::get("/catalog_characteristic/del/{id}", [\App\Http\Controllers\CatalogCharacteristicController::class, "actionDel"])->name("catalog_characteristic.del");

    Route::get("/catalog_characteristic_item/list/{id}", [\App\Http\Controllers\CatalogCharacteristicItemController::class, "actionList"])->name("catalog_characteristic_item.list");
    Route::post("/catalog_characteristic_item/list/{id}", [\App\Http\Controllers\CatalogCharacteristicItemController::class, "actionDND"]);
    Route::get("/catalog_characteristic_item/add", [\App\Http\Controllers\CatalogCharacteristicItemController::class, "actionAddGet"])->name("catalog_characteristic_item.add");
    Route::post("/catalog_characteristic_item/add", [\App\Http\Controllers\CatalogCharacteristicItemController::class, "actionAddPost"]);
    Route::get("/catalog_characteristic_item/edit/{id}", [\App\Http\Controllers\CatalogCharacteristicItemController::class, "actionEditGet"])->name("catalog_characteristic_item.edit");
    Route::post("/catalog_characteristic_item/edit/{id}", [\App\Http\Controllers\CatalogCharacteristicItemController::class, "actionEditPost"]);
    Route::get("/catalog_characteristic_item/del/{id}", [\App\Http\Controllers\CatalogCharacteristicItemController::class, "actionDel"])->name("catalog_characteristic_item.del");

    Route::get("/admin/list", [\App\Http\Controllers\AdminController::class, "actionList"])->name("admin.list");
    Route::get("/admin/add", [\App\Http\Controllers\AdminController::class, "actionAddGet"])->name("admin.add");
    Route::post("/admin/add", [\App\Http\Controllers\AdminController::class, "actionAddPost"]);
    Route::get("/admin/edit/{id}", [\App\Http\Controllers\AdminController::class, "actionEditGet"])->name("admin.edit");
    Route::post("/admin/edit/{id}", [\App\Http\Controllers\AdminController::class, "actionEditPost"]);
    Route::get("/admin/del/{id}", [\App\Http\Controllers\AdminController::class, "actionDel"])->name("admin.del");

    Route::get("/currency_rate/list/{id}", [\App\Http\Controllers\CurrencyRateController::class, "actionList"])->name("currency_rate.list");
    Route::get("/currency_rate/add/{id}", [\App\Http\Controllers\CurrencyRateController::class, "actionAddGet"])->name("currency_rate.add");
    Route::post("/currency_rate/add/{id}", [\App\Http\Controllers\CurrencyRateController::class, "actionAddPost"]);
    Route::get("/currency_rate/edit/{id}", [\App\Http\Controllers\CurrencyRateController::class, "actionEditGet"])->name("currency_rate.edit");
    Route::post("/currency_rate/edit/{id}", [\App\Http\Controllers\CurrencyRateController::class, "actionEditPost"]);
    Route::get("/currency_rate/del/{id}", [\App\Http\Controllers\CurrencyRateController::class, "actionDel"])->name("currency_rate.del");

    Route::get("/field/merchant/{action?}", \App\Http\Controllers\FieldMerchantController::class)->name("field.merchant");
    Route::get("/field/catalog/{action?}", \App\Http\Controllers\FieldCatalogController::class)->name("field.merchant1");
    Route::get("/field/shipping_method/{action?}", \App\Http\Controllers\FieldShippingMethodController::class)->name("field.shipping_method");
    Route::get("/field/catalog_characteristic/{action?}", \App\Http\Controllers\FieldCatalogCharacteristicController::class)->name("field.catalog_characteristic");
    Route::get("/field/currency/{action?}", \App\Http\Controllers\FieldCurrencyController::class)->name("field.currency");

});

Route::get('/lang/{language}', [\App\Http\Controllers\LanguageController::class, "setLanguage"])->name("lang");

Route::get("/shop/{id}.html", [\App\Http\Controllers\ShopController::class, "actionItem"])->name("shop.item");
Route::get("/shop/find", [\App\Http\Controllers\ShopController::class, "actionFind"])->name("shop.find");
Route::get("/shop/{id}", [\App\Http\Controllers\ShopController::class, "actionList"])->name("shop.list");

Route::group(["prefix" => "feedback"], function () {
    Route::post("/create/{id}", [\App\Http\Controllers\FeedbackController::class, "create"])->name("feedback.create");
});

Route::get("/favorites/add", [\App\Http\Controllers\FavoritesController::class, "addFavorites"])->name("favorites.add");
Route::get("/favorites/delete", [\App\Http\Controllers\FavoritesController::class, "deleteFavorites"])->name("favorites.delete");


Route::group(['middleware' => 'role-user'], function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, "actionIndexGet"])->name("cart.index");
    Route::post('/cart', [\App\Http\Controllers\CartController::class, "actionIndexPost"])->name('cart.newOrder');
    Route::get('/cart/calculate', [\App\Http\Controllers\CartController::class, "actionCalculate"])->name('cart.calculate');
    Route::get('/cart/done', [\App\Http\Controllers\CartController::class, "actionDone"])->name("cart.done");
});


Route::post('/cart/add', [\App\Http\Controllers\CartController::class, "actionAdd"]);
Route::post('/cart/set', [\App\Http\Controllers\CartController::class, "actionSet"]);
Route::post('/cart/del', [\App\Http\Controllers\CartController::class, "actionDel"]);


Route::get('/contact-us', [\App\Http\Controllers\ContactController::class, "index"])->name("contactus.index");

Route::get('/about-us', [\App\Http\Controllers\AboutUsController::class, "index"]);
Route::get('/requisites', [\App\Http\Controllers\RequisitesController::class, "index"]);

Route::group(["prefix" => "kazpost"], function () {
    Route::get('/test', [\App\Http\Controllers\KazPostController::class, "test"]);
    Route::get('/city', [\App\Http\Controllers\KazPostController::class, "city"]);
    Route::get('/enums', [\App\Http\Controllers\KazPostController::class, "enums"]);
    Route::get('/calc', [\App\Http\Controllers\KazPostController::class, "calc"]);
});


Route::any('{page}', [\App\Http\Controllers\ErrorController::class, "error404"])->where('page', '(.*)');



 Route::get('/color/shop', [\App\Http\Controllers\ShopController::class, "getColorBySize"]);


