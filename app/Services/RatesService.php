<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\CurrencyRate;

class RatesService
{

    public function __construct(public CurrencyRate $model)
    {
    }

    public function getRateTurkey($toCurrency)
    {
        $result = $this->model
            ->query()
            ->where('currency_id','=',2)
            ->where('currency_to_id','=',$toCurrency)
            ->first();

        return $result->rate_end ?? 1;
    }

}
