<?php

namespace App\Services;

use App\Clients\KazPost;
use App\Mail\NewOrderMail;
use App\Models\Catalog;
use App\Models\CatalogItem;
use App\Models\Country;
use App\Models\DeliveryPrice;
use App\Models\KPLocation;
use App\Models\KPPostCode;
use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\OrderItem;
use App\Models\User;
use App\Requests\KazPostRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class OrderService
{
    public function __construct(
    private KazPostRequest $kazPostRequest,
)
{
}
    public static function createOrder($data)
    {
            $cart = CartService::getCart();

            $userOrders = array();
            $merchantOrders = array();
            $adminOrders = array();
            foreach ($cart["items"] as $item){
                $rw = CatalogItem::query()->where('id', $item->id)->value('weight');
                $client = new KazPost();
                $real_weight = strlen($rw) ? $rw : '200';
                $price = str_replace(" ", '', $item->price);
                $postcode = KPPostCode::query()->where('id', $data["postcode_id"])->value('postcode');
                $td = DeliveryPrice::query()->where('id', 1)->value('gr_price');
                $kps = $client->getPostRate($real_weight, $price, $postcode);
                if (isset($kps->Sum)) {
                    $delivery_price = $kps->Sum;
                    $order = new Order();
                    $order->user_id = Auth::id() ?? 0;
                    $order->status = 2;
                    $order->surname = $data["surname"];
                    $order->name = $data["name"];
                    $order->middle_name = $data["middle_name"];
                    $order->phone = preg_replace("/[^,.0-9]/", '', $data["phone"]);
                    $order->email = $data["email"];
                    $order->country_id = $data["country_id"];
                    $order->country_name = Country::query()->where('id', intval($data["country_id"]))->value('name_ru');
                    $order->city_name = KPLocation::query()->where('id', $data["city_id"])->value('name');
                    $order->city_id = $data["city_id"];
                    $order->region_name = KPLocation::query()->where('id', $data["region_id"])->value('name');
                    $order->region_id = $data["region_id"];
                    $order->area_name = KPLocation::query()->where('id', $data["area_id"])->value('name');
                    $order->area_id = $data["area_id"];
                    $order->real_weight = $real_weight;
                    $order->article = $item->article;
                    $order->merchant = User::query()->where('id', $item->user_id)->value('name');
                    $order->merchant_id = $item->user_id;
                    $order->street = $data["street"];
                    $order->house_number = $data["house_number"];
                    $order->room = $data["room"];
                    $order->postcode_id = $data["postcode_id"];
                    $order->postcode = $postcode;
                    $order->time = $data["time"] ?? '00:00';
                    $order->comment = $data["comment"];
                    $order->pickup = $data["pickup"] ?? "N";
                    $order->delivery_price = doubleval($delivery_price) ?? '1000';
                    $order->tr_delivery_price = doubleval($real_weight) * $td;
                    $order->price = $price;
                    $order->save();

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
                    $orderItem->price = floatval($price);
                    $order->items()->save($orderItem);

                    $calculateCommission = new OrderCommission();
                    $calculateCommission->order_id = $order->id;
                    $calculateCommission->product_id = $orderItem->catalog_item_id;

                    array_push($userOrders,$order->id);
                    array_push($adminOrders,$order->id);

                    $merchant = User::where('id','=',$item->user->id)
                        ->first();

                    array_push($merchantOrders,[$merchant->id => $order->id]);

                    $product = CatalogItem::where('id','=',$calculateCommission->product_id)
                        ->first();
                    $percent = Catalog::where('id','=',$product->catalog_id)
                        ->first();
                    $calculateCommission->merchant_id = $product->user_id;
                    $calculateCommission->commission_price = $price * ($percent->commission ?? 0) / 100;

                    $calculateCommission->total_price = $price - $calculateCommission->commission_price;
                    $calculateCommission->save();
                } else {
                    return $kps->ResponseInfo->ResponseText;
                }

            }

            $user = new User();
            $user->order_id = implode(',',$userOrders);
            $user->first_name = $data["name"];
            $user->last_name = $data["surname"];
            $user->email = $data["email"];
            try {
                Mail::to($user->email)->send(new NewOrderMail($user,'user'));
            }catch (\Exception $e){

            }

            $admin = User::where('role','=','admin')
                ->first();
            try {

                foreach ($merchantOrders as $key=> $value){
                    $merchant = User::where('id','=',$key)
                        ->first();
                    $user->order_id = $value;
                    Mail::to($merchant->email)->send(new NewOrderMail($merchant,$merchant->role));
                }

                $user->order_id = implode(',',$adminOrders);
                Mail::to($admin->email)->send(new NewOrderMail($admin,$admin->role));
            }catch (\Exception $e){

            }

    }

    public static function buyOrder($id)
    {
        $order = Order::query()->findOrFail($id);
        //$order->status = 2;
        $order->save();

    }

    public function tracking()
    {
        $actualOrders = Order
            ::where('created_at', '>' ,Carbon::now()->subDays(20))
            ->orderByDesc('created_at')
            ->get()
            ->whereNotIn('status', [
               1,2,3,6,7
            ]);

        foreach ($actualOrders as $order)
        {
            $this->kazPostRequest->tracking($order);
        }
    }
}
