<?php

namespace App\Services;

use App\Models\Currency;

class CurrencyService
{

    public function __construct(public Currency $model)
    {
    }

    public function getAllCurrency(){
        return $this->model
            ->query()
            ->all();
    }


    public function getCurrencyByCountry(){
        $locale = app()->getLocale();
        switch ($locale){
            case 'ru' :
                return 3;
            case 'kz':
                return 1;
            default:
                return 2;
        }
    }

    public function getCurrencyById($id){
        return $this->model
            ->query()
            ->find($id);
    }

    public function getTurkeyCurrency(){
        return $this->model
            ->query()
            ->find(2);
    }


}
