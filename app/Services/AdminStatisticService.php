<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AdminStatisticService
{
    public function __construct(protected User $user,protected OrderItem $order)
    {
    }

    public function getCountMerchant($month,$days){
        return $this->user->query()->where('role','=','merchant')
                ->when($month != null,function ($query) use ($days){
                    if ($days == 60){
                        $query->where('created_at', '<=', Carbon::now()->subDays(30)->toDateTimeString());
                        $query->where('created_at', '>=', Carbon::now()->subDays($days)->toDateTimeString());
                    }else{
                        $query->where('created_at', '>=', Carbon::now()->subDays($days)->toDateTimeString());
                    }

                })
                ->count();
    }

    public function getCountUser($month,$days){
        return $this->user->query()->where('role','=','user')
            ->when($month != null,function ($query) use ($days){
                if ($days == 60){
                    $query->where('created_at', '<=', Carbon::now()->subDays(30)->toDateTimeString());
                    $query->where('created_at', '>=', Carbon::now()->subDays($days)->toDateTimeString());
                }else{
                    $query->where('created_at', '>=', Carbon::now()->subDays($days)->toDateTimeString());
                }
            })
            ->count();
    }


    public function getPriceCommission($month,$days){
        return $this->order->query()->when($month != null,function ($query) use ($days){
                if ($days == 60){
                    $query->where('created_at', '<=', Carbon::now()->subDays(30)->toDateTimeString());
                    $query->where('created_at', '>=', Carbon::now()->subDays($days)->toDateTimeString());
                }else{
                    $query->where('created_at', '>=', Carbon::now()->subDays($days)->toDateTimeString());
                }
            })
            ->sum('price');
    }

}
