<?php


namespace App\Http\Controllers;


use App\Clients\KazPost;
use App\Http\Requests\CartOderPostRequest;
use App\Jobs\newOrderJob;
use App\Models\City;
use App\Models\Country;
use App\Models\CurrencyRate;
use App\Models\DeliveryPrice;
use App\Models\KPLocation;
use App\Models\KPPostCode;
use App\Mail\NewOrderMail;
use App\Models\CatalogItem;
use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\OrderItem;
use App\Models\ProductItem;
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

        //$token = $this->paymentService->authToken();
        //var_dump($token);
        //$url = $this->paymentService->makePayment(null,$request->all());
        //var_dump($url);

        newOrderJob::dispatch($request->all(),CartService::getCart(),Auth::user());

        if (Auth::check()){
            $this->service->update($request->all());
        }

        return ["redirect" => route("cart.done")];
    }

    public function actionDone()
    {
        $this->cart->delete(Auth::user()->id ?? 0);
        CartService::clear();
        return view("cart.done");
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
