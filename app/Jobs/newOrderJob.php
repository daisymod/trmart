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
use App\Models\OrderComissionCopy;
use App\Models\OrderCommission;
use App\Models\OrderCopy;
use App\Models\OrderItem;
use App\Models\OrderItemCopy;
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


    public function __construct(public $hash)
    {

    }

    public function handle()
    {
        $userOrders = array();
        $adminOrders = array();

        $order = OrderCopy::where('hash','=',$this->hash)
                ->get()->toArray();

        $orders = array();
        foreach ($order as $item){

            Log::info(print_r($item['id'],true));
            $orderItems = OrderItemCopy::where('order_id','=',$item['id'])
                ->get()->toArray();

            $orderCommissions = OrderComissionCopy::where('order_id','=',$item['id'])
                ->get()->toArray();


            unset($item['id']);
            unset($item['hash']);
            $newOrder  =  Order::query()->create($item);

            foreach ($orderItems as $orderItem){
                $orderItem['order_id'] = $newOrder->id;
                unset($orderItem['id']);
                OrderItem::query()->create($orderItem);
            }

            foreach ($orderCommissions as $orderCommissionItem){
                $orderCommissionItem['order_id'] = $newOrder->id;
                unset($orderCommissionItem['id']);
                OrderCommission::query()->create($orderCommissionItem);
            }
            array_push($userOrders,$newOrder->id);
            array_push($adminOrders,$newOrder->id);

            $merchant = User::where('id','=',$newOrder->merchant_id)
                            ->first();
            try {

                $merchant->order_id = $newOrder->id;
                Mail::to($merchant->email)->send(new MerchantNewOrder($merchant));
            } catch (\Exception $e) {

            }
        }

        try {
            $user = User::where('id','=',$newOrder->user_id)
                ->first();
            $user->order_id = implode(',', $userOrders);
            Mail::to($user->email)->send(new UserNewOrder($user));

            $admin = User::where('id','=',1)
                ->first();
            $admin->order_id = implode(',', $adminOrders);
            Mail::to($admin->email)->send(new AdminNewOrder($admin));
        } catch (\Exception $e) {

        }


    }

}
