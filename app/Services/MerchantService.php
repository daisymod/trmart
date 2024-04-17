<?php

namespace App\Services;



use App\Models\CatalogItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MerchantService
{
    public function __construct(protected CatalogItem $item,protected OrderItem $model,protected Order $order,)
    {
    }

    public function getStatisticByPeriod($month){

        return $this->model->query()
            ->when($month != null,function ($query){
                $query->where('created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString());
            })
            ->whereHas('item.merchant',function ($query){
                $query->where('user_id','=',Auth::user()->id);
            })
            ->sum('price');
    }

    public function getStatisticByPeriodUser($month){

         $price = $this->order->query()
             ->when($month != null,function ($query){
                 $query->where('created_at', '>=', Carbon::now()->subDays(30)->toDateTimeString());
             })
            ->where('user_id', '=', Auth::user()->id)
            ->get();
         return $price->sum('price') + $price->sum('delivery_price') + $price->sum('tr_delivery_price');
    }

    public function getBuyOrder(){
        return $this->order->query()
            ->where('user_id', '=', Auth::user()->id)
            ->count();
    }


    public function getCountVerifyProduct(){
        return $this->item->query()
            ->where('user_id','=',Auth::user()->id)
            ->count();
    }



}
