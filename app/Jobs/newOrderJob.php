<?php

namespace App\Jobs;

use App\Clients\KazPost;
use App\Mail\AdminNewOrder;
use App\Mail\MerchantNewOrder;
use App\Mail\NewOrderMail;
use App\Mail\UserNewOrder;
use App\Models\Catalog;
use App\Models\CatalogItem;
use App\Models\Country;
use App\Models\CurrencyRate;
use App\Models\DeliveryPrice;
use App\Models\KPLocation;
use App\Models\KPPostCode;
use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\TurkeyRegion;
use App\Models\CatalogCharacteristicItem;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class newOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(public array $request, public $cart,public User $user)
    {

    }

    public function handle()
    {
        $cart = $this->cart;
        $deliveryPrices = session()->get('deliveryPrices');
        $userOrders = array();
        $merchantOrders = array();
        $adminOrders = array();
        foreach ($cart["items"] as $item) {
            $coefficient = CurrencyRate::where('currency_id','=',2)
                ->where('currency_to_id','=',1)
                ->first();
            $rw = CatalogItem::query()->where('id', $item->id)->value('weight');
            $real_weight = strlen($rw) ? $rw : '200';
            $price = str_replace(" ", '', $item->price);
            $postcode = KPPostCode::query()->where('id', $this->request["postcode_id"])->value('postcode');
            $td = DeliveryPrice::query()->where('id', 1)->value('gr_price');
            $order = new Order();
            $order->user_id = $this->user->id;
            $order->status = 1;
            $order->surname = $this->request["surname"];
            $order->name = $this->request["name"];
            $order->middle_name = $this->request["middle_name"];
            $order->phone = preg_replace("/[^,.0-9]/", '', $this->request["phone"]);
            $order->email = $this->request["email"];
            $order->country_id = $this->request["country_id"];
            $order->country_name = Country::query()->where('id', intval($this->request["country_id"]))->value('name_ru');
            $order->city_name = KPLocation::query()->where('id', $this->request["city_id"])->value('name');
            $order->city_id = $this->request["city_id"];
            $order->region_name = KPLocation::query()->where('id', $this->request["region_id"])->value('name');
            $order->region_id = $this->request["region_id"];
            $order->area_name = KPLocation::query()->where('id', $this->request["area_id"])->value('name');
            $order->area_id = $this->request["area_id"];
            $order->real_weight = $real_weight;
            $order->article = $item->article;
            $order->merchant = User::query()->where('id', $item->user_id)->value('name');
            $order->merchant_id = $item->user_id;
            $order->street = $this->request["street"];
            $order->house_number = $this->request["house_number"];
            $order->room = $this->request["room"];
            $order->postcode_id = $this->request["postcode_id"];
            $order->postcode = $postcode;
            $order->time = $this->request["time"] ?? '00:00';
            $order->comment = $this->request["comment"];
            $order->pickup = $this->request["pickup"] ?? "N";
            $order->delivery_price =  intval($this->request["delivery"]) / count($cart["items"]) * $item->count ?? null;
            $order->tr_delivery_price = $this->request["deliveryTr"] ?? null;
            $order->payment = ceil($price * $coefficient->rate_end) * $item->count;

            $product = CatalogItem::where('id','=',$item->id)
                    ->first();
            $sale = 0 ;
            if ($product->sale > 0){
                $sale = $product->price * $product->sale / 100;
            }
            $order->sale = ceil($sale * $coefficient->rate_end) * $item->count;

            $order->price = ceil($price * $coefficient->rate_end) * $item->count;
            $order->save();
//                $kpLetter = $client->getAddrLetter($order);
//                if ($kpLetter->Barcodes) {
//                    $order->barcode = $kpLetter->Barcodes;
//                    //$order->pdf = base64_decode($kpLetter->AddrLetPdfs);
//                    //$order->res = base64_decode($kpLetter);
//                    $order->save();
//                } else {
//                    throw new \Exception($kpLetter->ResponseInfo->ResponseText);
//                }

            $orderItem = new OrderItem();
            $orderItem->user_id = $item->user->id;
            $orderItem->user_name = $item->user->name;
            $orderItem->catalog_item_id = $item->id;
            $orderItem->image = $item->image;
            $orderItem->name = $item->lang("name");
            $orderItem->article = $item->article;

            $orderItem->size = $item->size;
            $orderItem->color = $item->color;
            $orderItem->count = $item->count;

            $orderItem->price = ceil($price) * $item->count;

            $orderItem->price_tenge = ceil($price * $coefficient->rate_end) * $item->count;

            $order->items()->save($orderItem);
            $calculateCommission = new OrderCommission();
            $calculateCommission->order_id = $order->id;
            $calculateCommission->product_id = $orderItem->catalog_item_id;



	    $size = CatalogCharacteristicItem::where('catalog_characteristic_id','=',16)
            	->where('name_tr','=',$item->size)
            	->first();
            $product_item = ProductItem::where('item_id','=',$item->id)
                ->where('size','=',$size->id)
                ->where('color','=',$item->color)
                ->first();

            if (!empty($product_item->color)){
                $product_item->count - $item->count < 0 ? $product_item->count = 0 : $product_item->count = $product_item->count - $item->count;
                $product_item->save();
            }


            array_push($userOrders, $order->id);
            array_push($adminOrders, $order->id);

            $merchant = User::where('id', '=', $item->user->id)
                ->first();

            array_push($merchantOrders, [$merchant->id => $order->id]);

            $product = CatalogItem::where('id', '=', $calculateCommission->product_id)
                ->first();
            $percent = Catalog::where('id', '=', $product->catalog_id)
                ->first();
            $calculateCommission->merchant_id = $product->user_id;
            $calculateCommission->commission_price = (ceil($price * $coefficient->rate_end) * $item->count) * ($percent->commission ?? 0) / 100;

            $calculateCommission->total_price = (ceil($price * $coefficient->rate_end) * $item->count)  - $calculateCommission->commission_price;
            $calculateCommission->save();

        }

        $user = new User();
        $user->order_id = implode(',', $userOrders);
        $user->name = $this->request["name"];
        $user->s_name = $this->request["surname"];
        $user->email = $this->request["email"];
        $user->lang = $this->user->lang;
        try {
            Mail::to($user->email)->send(new UserNewOrder($user));
        } catch (\Exception $e) {

        }

        $admin = User::where('role', '=', 'admin')
            ->first();
        try {

            foreach ($merchantOrders as $key => $value) {
                $merchant = User::where('id', '=', $key)
                    ->first();
                $merchant->order_id = $value;
                Mail::to($merchant->email)->send(new MerchantNewOrder($merchant));
            }
            $admin->order_id = implode(',', $adminOrders);
            Mail::to($admin->email)->send(new AdminNewOrder($admin));
        } catch (\Exception $e) {

        }
        $user = new User;
        $user->order_id = implode(',', $userOrders);
        $user->name = $this->request["name"];
        $user->s_name = $this->request["surname"];
        $user->email = $this->request["email"];
        $user->lang = $this->user->lang;
        try
        {
            Mail::to($user->email)->send(new UserNewOrder($user));
        }catch
        (\Exception $e){

        }
        $admin = User::where('role', '=', 'admin')
            ->first();
        try {

            foreach ($merchantOrders as $key => $value) {
                $merchant = User::where('id', '=', $key)
                    ->first();
                $merchant->order_id = $value;
                Mail::to($merchant->email)->send(new MerchantNewOrder($merchant));
            }

            $admin->order_id = implode(',', $adminOrders);
            Mail::to($admin->email)->send(new AdminNewOrder($admin));
        } catch (\Exception $e) {
        }
    }

}
