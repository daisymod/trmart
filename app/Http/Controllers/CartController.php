<?php


namespace App\Http\Controllers;


use App\Clients\KazPost;
use App\Http\Requests\CartOderPostRequest;
use App\Jobs\newOrderCopyJob;
use App\Jobs\newOrderJob;
use App\Models\Basket;
use App\Models\Catalog;
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

class CartController extends Controller
{
    public function __construct(protected UserService $service,protected UserCartService $cart,protected PaymentRequests $paymentService)
    {
    }


    public function actionAdd()
    {

        $item = ProductItem::where('item_id','=',request()->get("id"))
                    ->where('size','=',request()->get("size"))
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
        //session()->remove("order_data");
        $countries = Country::query()
            ->select('id', 'name_ru')
            ->get()
            ->values();;
        if (Auth::check() && Auth::user()->country_id === 3) {
            $regions   = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', 0)
                ->select('id', 'name')
                ->get()
                ->values();
            $areas     = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', KPLocation::query()->where('id', Auth::user()->area_id)->value('parent_id'))
                ->select('id', 'name')
                ->get()
                ->values();
            $cities    = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', KPLocation::query()->where('id',  Auth::user()->city_id)->value('parent_id'))
                ->select('id', 'name')
                ->get()
                ->values();
            $postCodes = KPPostCode::query()
                ->orderBy('title')
                ->where('kp_locations_id', KPPostCode::query()->where('id',  Auth::user()->postcode_id)->value('kp_locations_id'))
                ->select('id', 'title')
                ->get()
                ->values();
        } else {
            $areas = [];
            $regions = [];
            $cities = [];
            $postCodes = [];
        }

        $cartCheckCount = $this->cartCheckCount();

        return view("cart.index", compact("countries", "cities", "regions", "areas", "postCodes", 'cartCheckCount'));
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


        Log::info(print_r($cart['price'],true));
        Log::info(print_r(ceil($priceDelivery),true));

        $requestPay = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $requestPay->setLocale(\Iyzipay\Model\Locale::EN);
        $requestPay->setConversationId(rand(0,9999999999));
        $requestPay->setPrice($cart['price']);
        $requestPay->setPaidPrice(intval($cart['price']) + ceil($priceDelivery));
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
            $firstBasketItem->setSubMerchantKey('+nPmcvksgu5VDMAFwgfT8N7689I=');
            $firstBasketItem->setSubMerchantPrice(intval($item['price']));
            $firstBasketItem->setName($item['name_en']);
            $category = Catalog::where('id','=',$item['catalog_id'])
                ->first();
            $firstBasketItem->setCategory1($category->name_en);
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice(intval($cart['price']));
            $basketItems[$index] = $firstBasketItem;
        }


        $requestPay->setBasketItems($basketItems);

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($requestPay, $options);
        Log::info(print_r($checkoutFormInitialize,true));
        newOrderCopyJob::dispatch($request->all(),CartService::getCart(),Auth::user(),$hash);
        if (Auth::check()){
            $this->service->update($request->all());
        }
        return ["redirect" => $checkoutFormInitialize->getPaymentPageUrl()];


        //newOrderJob::dispatch($request->all(),CartService::getCart(),Auth::user());


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
        foreach ($cart as $key => $item) {
            $count = $count + $item['count'];
            $product = CatalogItem::find($item['id']);
            $weight  = $product->weight * $item['count'];
            $price   = ceil(($product->price - ($product->price * $product->sale) / 100) * $coefficent->rate_end) * $item['count'];
            Log::info(print_r('price-'.$price,true));
            $kps = $client->getPostRate($weight, $price, Auth::user()->postcode);
            if (isset($kps->Sum)) {
                $test[] = [$count, $product->id, $weight, $price, $kps->Sum];
                $deliveryPrice   = doubleval($kps->Sum);
                $deliveryTrPrice = doubleval($product->weight) * $td * $item['count'];
                Log::info(print_r('tenge -'.$deliveryPrice,true));
                Log::info(print_r('tr -'.$deliveryTrPrice,true));
                $delivery = $delivery + ($deliveryPrice * $item['count']);
                $deliveryTr = $deliveryTr + ($deliveryTrPrice * $item['count']);
                $arr['items'][$key] = $deliveryPrice + $deliveryTrPrice;
                $total = $total + (($deliveryPrice + $deliveryTrPrice));
            } else {
                return response()->json(['name' => [$kps->ResponseInfo->ResponseText]], 422);
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
