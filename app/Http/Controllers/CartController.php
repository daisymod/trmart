<?php


namespace App\Http\Controllers;


use App\Clients\KazPost;
use App\Http\Requests\CartOderPostRequest;
use App\Jobs\newOrderCopyJob;
use App\Jobs\newOrderJob;
use App\Models\AutoDeliverySettings;
use App\Models\Basket;
use App\Models\Catalog;
use App\Models\CatalogCharacteristicItem;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\CurrencyRate;
use App\Models\DeliveryPrice;
use App\Models\KPLocation;
use App\Models\KPPostCode;
use App\Mail\NewOrderMail;
use App\Models\CatalogItem;
use App\Models\MerchantKey;
use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\User;
use App\Pay\Requests\PaymentRequests;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\UserCartService;
use App\Services\UserService;
use BeetleCore\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function __construct(protected UserService $service,protected UserCartService $cart,protected PaymentRequests $paymentService)
    {
    }


    public function actionAdd()
    {
        $itemAdd = CatalogItem::where('id','=',request()->get("id"))
                ->first();

        $data = CartService::getCart();
        if (count($data['items']) >0){
            foreach ($data['items'] as $item){
                if ($item->catalog->type_delivery != $itemAdd->catalog->type_delivery){
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        trans('system.t10')
                    ]);
                    throw $error;
                }
            }
        }


        session()->forget('deliveryCalDate');
        session()->forget('deliveryPrices');

        $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
                            ->where('name_ru','=',request()->get("size"))
                            ->orWhere('name_kz','=',request()->get("size"))
                            ->orWhere('name_tr','=',request()->get("size"))
                            ->first();

        $item = ProductItem::where('item_id','=',request()->get("id"))
                    ->where('size','=',$size->id)
                    ->where('color','=',request()->get("color"))
                    ->first();


        $data = CartService::add(request()->get("id"), request()->get("count"), request()->get("size"), request()->get("color"));
        if ($data == 422){
            $error = \Illuminate\Validation\ValidationException::withMessages([
                trans('system.not-pr-found')
            ]);
            throw $error;
        }
        $this->cart->delete(Auth::user()->id ?? 0);
        $this->cart->create($data);

        return $data;
    }

    public function actionIndexGet()
    {
        if (Auth::check()){
            $countries = Country::query()
                ->select('id', 'name_ru')
                ->where('id','=',Auth::user()->country_id)
                ->first();
            if (!empty($countries->name_ru)){
                $country_id = $countries->name_ru;
            }else{
                $country_id = '';
            }
        }else{
            $country_id = '';
        }
        //session()->remove("order_data");


        if (Auth::check() && Auth::user()->country_id === 3) {
            $regions   = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', 0)
                ->select('id', 'name')
                ->where('id','=',Auth::user()->region_id)
                ->first();

            if (!empty($regions->name)){
                $region_id = $regions->name;
            }else{
                $region_id = '';
            }

            $areas     = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', KPLocation::query()->where('id', Auth::user()->area_id)->value('parent_id'))
                ->select('id', 'name')
                ->where('id','=',Auth::user()->area_id)
                ->first();

            if (!empty($areas->name)){
                $area_id = $regions->name;
            }else{
                $area_id = '';
            }

            $cities    = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', KPLocation::query()->where('id',  Auth::user()->city_id)->value('parent_id'))
                ->select('id', 'name')
                ->where('id','=',Auth::user()->city_id)
                ->first();

            if (!empty($cities->name)){
                $city_id = $cities->name;
            }else{
                $city_id = '';
            }

            $postCodes = KPPostCode::query()
                ->orderBy('title')
                ->select('id', 'title')
                ->where('id','=',Auth::user()->postcode_id)
                ->first();

            if (!empty($postCodes->title)){
                $postcode_id = $postCodes->title;
            }else{
                $postcode_id = '';
            }

        } else {
            $areas = [];
            $regions = [];
            $cities = [];
            $postCodes = [];
        }

        $cartCheckCount = $this->cartCheckCount();
        $data = CartService::getCart();

        $delivery_auto = 0;
        foreach ($data['items'] as $item){
            $weight = (($item->length != null ?  $item->length : 1) *
            ($item->width != null ?  $item->width : 1) * ($item->height != null ?  $item->height : 1)) / 5000 ;

            $weightReal = (($item->weight != null ?  $item->weight : 1)) / 1000;

            if ($weightReal > $weight){
                $weight = $weightReal;
            }

            $getPrice = AutoDeliverySettings::where('from','<=',$weight)
                ->where('to','>=',$weight)
                ->first();

            if (!empty($getPrice->price)){
                $delivery_auto += $getPrice->price * $weight;
            }else{
                $getPrice = AutoDeliverySettings::orderByDesc('price')
                    ->first();
                if (!empty($getPrice->price)){
                    $delivery_auto += $getPrice->price * $weight;
                }
            }
        }

        if (count($data['items']) > 0) {
            $getCurrentDelivery = $data['items'][0]->catalog;
            $type = $getCurrentDelivery->type_delivery;
        }else{
            $type = 1;
        }
        return view("cart.index", compact(
            "city_id",'area_id','postcode_id','region_id',"country_id",
            "delivery_auto","type","countries", "cities", "regions", "areas", "postCodes", 'cartCheckCount'));
    }

    public static function getVKData($method, $params = [])
    {
        $params["v"] = "5.81";

        $params["access_token"] = env("VK_ACCESS_TOKEN");
        $key = static::class . md5($method . http_build_query($params));
        $result = Cache::get($key, false);
        if ($result === false) {
            $url = "https://api.vk.com/method/$method?" . http_build_query($params);
            $ch = curl_init(); // инициализация
            curl_setopt($ch, CURLOPT_URL, $url); // адрес страницы для скачивания
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);   //TIMEOUT
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //Переходим по редиректам
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // нам нужно вывести загруженную страницу в переменную
            $result = curl_exec($ch);
            curl_close($ch);
            Cache::put($key, $result, 60 * 60 * 24);
        }
        return $result;
    }


    public function actionIndexPost(CartOderPostRequest $request)
    {
       $cart = CartService::getCart();

       $hash = uniqid();

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $basket = new Basket();
        $basket->save();
        $rates = CurrencyRate::where('id','=',2)
                ->first();

        $priceDelivery =  ($request->get('delivery') + $request->get('deliveryTr')) / $rates->rate_end;

        $options = new \Iyzipay\Options();
        $options->setApiKey("bndv9YASgfDvKS6ZWzPiq3J4Ow3wU4q2");
        $options->setSecretKey("ixAzd6UNXi1vhRpVZ2tUe5kcAO6Pl4Fd");
        $options->setBaseUrl("https://api.iyzipay.com");

        $requestPay = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $requestPay->setLocale(\Iyzipay\Model\Locale::EN);
        $requestPay->setConversationId(rand(0,9999999999));

        Log::info(print_r($cart['price'],true));
        Log::info(print_r(floatval(str_replace(' ','',$cart['price'])),true));


        $requestPay->setPrice(floatval(str_replace(' ','',$cart['price'])));
        $requestPay->setPaidPrice(floatval(str_replace(' ','',$cart['price'])) + ceil($priceDelivery));

        $requestPay->setCurrency(\Iyzipay\Model\Currency::TL);
        $requestPay->setBasketId($basket->id);
        $requestPay->setBasketItems($cart['count']);
        $requestPay->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $requestPay->setCallbackUrl("https://turkiyemart.com/callback?hash=".$hash);
        $requestPay->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(Auth::user()->id);
        $buyer->setName($request->get('name'));
        $buyer->setSurname($request->get('surname'));
        $buyer->setGsmNumber('+'.$request->get('phone'));
        $buyer->setEmail($request->get('email'));
        $buyer->setIdentityNumber(rand(10000,99999999));
        $buyer->setLastLoginDate(Carbon::now()->format('Y-m-d h:i:s'));
        $buyer->setRegistrationDate(Auth::user()->created_at->format('Y-m-d h:i:s'));
        $buyer->setRegistrationAddress(Auth::user()->address_invoice.' '.Auth::user()->house_number.' '.Auth::user()->room);
        $buyer->setIp($ip);
        $buyer->setCity(Auth::user()->city_title);
        $buyer->setCountry(Auth::user()->country_title);
        $buyer->setZipCode(Auth::user()->postcode);
        $requestPay->setBuyer($buyer);

        $merchant = Company::where('user_id','=',$cart['items'][0]['user_id'])
            ->first();

        $merchantUser = User::where('id','=',$cart['items'][0]['user_id'])
            ->first();

        $key = MerchantKey::where('user_id','=',$cart['items'][0]['user_id'])
            ->first();

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($merchant->first_name.' '.$merchant->last_name);
        $shippingAddress->setCity($merchant->city);
        $shippingAddress->setCountry('Turkey');
        $shippingAddress->setAddress($merchant->city.' '.$merchant->street.' '.$merchant->number.' '.$merchant->office);
        $shippingAddress->setZipCode($merchantUser->postcode);
        $requestPay->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($merchant->first_name.' '.$merchant->last_name);
        $billingAddress->setCity($merchant->city);
        $billingAddress->setCountry('Turkey');
        $billingAddress->setAddress($merchant->city.' '.$merchant->street.' '.$merchant->number.' '.$merchant->office);
        $billingAddress->setZipCode($merchantUser->postcode);
        $requestPay->setBillingAddress($billingAddress);
        $index = 0;


        foreach ($cart['items'] as $item) {
            $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId($index);
            $firstBasketItem->setSubMerchantKey($key->key);
            $firstBasketItem->setSubMerchantPrice(floatval(str_replace(' ','',$item['price'])));

            $firstBasketItem->setName( 'turkiyemart');

            $firstBasketItem->setCategory1('turkiyemart');
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice(floatval(str_replace(' ','',$cart['price'])));
            $basketItems[$index] = $firstBasketItem;
        }

        $requestPay->setBasketItems($basketItems);
        Log::info(print_r($requestPay,true));
        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($requestPay, $options);
        Log::info(print_r($checkoutFormInitialize,true));
        newOrderCopyJob::dispatch($request->all(),CartService::getCart(),Auth::user(),$hash);
        if (Auth::check()){
            $this->service->update($request->all());
        }
        if (!empty($checkoutFormInitialize->getErrorMessage())){
            throw ValidationException::withMessages([$checkoutFormInitialize->getErrorMessage()]);
        }

        return ["redirect" => $checkoutFormInitialize->getPaymentPageUrl()];
    }

    public function actionDone()
    {
        $this->cart->delete(Auth::user()->id ?? 0);
        CartService::clear();
        return view("cart.done");
    }

    public function actionError()
    {
        return view("cart.error");
    }

    public function actionCallback(Request $request)
    {

        $response = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $response->setLocale(\Iyzipay\Model\Locale::EN);
        $response->setConversationId(rand(0,99999999));
        $response->setToken($request->get('token'));
        $options = new \Iyzipay\Options();

        $options->setApiKey("bndv9YASgfDvKS6ZWzPiq3J4Ow3wU4q2");
        $options->setSecretKey("ixAzd6UNXi1vhRpVZ2tUe5kcAO6Pl4Fd");
        $options->setBaseUrl("https://api.iyzipay.com");

        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($response, $options);
        Log::info(print_r($checkoutForm,true));

        if ($checkoutForm->getPaymentStatus() == 'SUCCESS'){
            newOrderJob::dispatch($request->get('hash'),$checkoutForm->getPaymentItems()[0]->paymentTransactionId ?? $checkoutForm->getPaymentId());
            return redirect(route("cart.done"));
        }else{
            return redirect(route("cart.error"));
        }
    }

    public function actionSet()
    {
        session()->forget('deliveryCalDate');
        session()->forget('deliveryPrices');
        $data = CartService::set(request()->get("key"), request()->get("count"));

        if ($data == 422){
            $error = \Illuminate\Validation\ValidationException::withMessages([
                trans('system.not-pr-found')
            ]);
            throw $error;
        }
        $this->cart->delete(Auth::user()->id ?? 0);
        $this->cart->create($data);
        return CartService::getCart();
    }

    public function actionDel(){
        session()->forget('deliveryCalDate');
        session()->forget('deliveryPrices');
        CartService::clear();
        $this->cart->delete(Auth::user()->id ?? 0);

        return CartService::getCart();
    }

    public function address()
    {
        $dadata = new \Dadata\DadataClient(env("DADATA_TOKEN"), env("DADATA_SECRET"));
        return ["suggestions" => $dadata->suggest("address", request("query"), 10)];
    }

    public function addressMoscow()
    {
        $dadata = new \Dadata\DadataClient(env("DADATA_TOKEN"), env("DADATA_SECRET"));
        return ["suggestions" => $dadata->suggest("address", request("query"), 10, ["locations" => ["region" => "москва"]])];
    }

    public function post()
    {
        $from = 123056;
        $to = request("code");
        return Helper::getUrl("https://delivery.pochta.ru/v2/calculate/tariff/delivery?json&object=27030&from=$from&to=$to&weight=1000&pack=10");
    }

    public function actionCalculate(Request $request)
    {
        session()->put('deliveryCalDate', Carbon::now()->format('d-m-Y'));
        $td  = DeliveryPrice::query()->where('id', 1)->value('gr_price');
        $client = new KazPost();
        $cart = session()->get("cart");
        $total = 0;
        $count = 0;
        $delivery = 0;
        $deliveryTr = 0;
        $arr = [];
        $coefficent = CurrencyRate::where('id','=',2)
            ->first(); // kzt
        $delivery_auto = 0;
        foreach ($cart as $key => $item) {
            $count = $count + $item['count'];
            $product = CatalogItem::find($item['id']);
            $weight = $product->weight * $item['count'];
            $price = ceil(($product->price - ($product->price * $product->sale) / 100) * $coefficent->rate_end) * $item['count'];
            if ($product->catalog->type_delivery == 1) {
                $kps = $client->getPostRate($weight, $price, Auth::user()->postcode);
                if (isset($kps->Sum)) {
                    $deliveryPrice   = doubleval($kps->Sum);
                    $deliveryTrPrice = doubleval($product->weight) * $td * $item['count'];
                    $delivery = $delivery + ($deliveryPrice * $item['count']);
                    $deliveryTr = $deliveryTr + ($deliveryTrPrice * $item['count']);
                    $arr['items'][$key] = $deliveryPrice + $deliveryTrPrice;
                    $total = $total + (($deliveryPrice + $deliveryTrPrice));
                } else {
                    return response()->json(['name' => [$kps->ResponseInfo->ResponseText]], 422);
                }
            }else {

                $weight = (($product->length != null ?  $product->length : 1) *
                    ($product->width != null ?  $product->width : 1) * ($product->height != null ?  $product->height : 1)) / 5000
                    * $item['count'];

                $weightReal = ((($product->weight != null ?  $product->weight : 1)) / 1000)  * $item['count'];

                if ($weightReal > $weight){
                    $weight = $weightReal;
                }

                $getPrice = AutoDeliverySettings::where('from','<=',$weight)
                    ->where('to','>=',$weight)
                    ->first();

                if (!empty($getPrice->price)){
                    $delivery_auto += $getPrice->price * $weight;
                }else{
                    $getPrice = AutoDeliverySettings::orderByDesc('price')
                        ->first();
                    if (!empty($getPrice->price)){
                        $delivery_auto += $getPrice->price * $weight;
                    }
                }

                $deliveryPrice   = 0;
                $deliveryTrPrice = ceil(doubleval($delivery_auto));
                $delivery = 0;
                $deliveryTr = ceil($deliveryTr + ($deliveryTrPrice));
                $arr['items'][$key] = ceil($deliveryPrice + $deliveryTrPrice);
                $total = ceil($total + (($deliveryPrice + $deliveryTrPrice)));
            }
        }
        $arr['count'] = $count;
        $arr['total'] = $total;
        $arr['delivery'] = $delivery;
        $arr['deliveryTr'] = $deliveryTr;
        session()->put('deliveryPrices', $arr);
        return response()->json($arr);

    }

    private function cartCheckCount() {
        $cart = session()->get("cart");
        if ($cart) {
            $cartCount = 0;
            $deliveryPrices = session()->get('deliveryPrices');
            foreach ($cart as $item) {
                $cartCount = $cartCount + $item['count'];
            }
            if ($deliveryPrices) {
                if ($cartCount === $deliveryPrices['count'] && session()->get('deliveryCalDate') === Carbon::now()->format('d-m-Y')) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
