<?php

namespace App\Services;

use App\Models\Catalog;

class CommissionService
{

    public function __construct(protected Catalog $model)
    {
    }

    public function getPercent($id){
        $percent = $this->model->query()
                ->where('id','=',$id)
                ->first();

        return $percent->commission;
    }


    public function calculateCommission($totalPrice,$percent){
        return ($totalPrice * $percent) / 100;
    }


}
