<?php

namespace App\Services;

use App\Models\OrderCommission;
use Illuminate\Support\Facades\Auth;

class OrderCommissionService
{

    public function __construct(protected OrderCommission $model)
    {
    }

    public function create($attributes){
        $item = $this->model->query()->create($attributes);
        return $item;
    }

    public function index($request){
        $result = $this->model->query()
                    ->where('merchant_id','=',Auth::user()->id)
                    ->when(!empty($request->order_id),function ($query) use ($request){
                        $query->where('order_id','=',$request->order_id);
                    });

        return $result;
    }



}
