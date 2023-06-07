<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function __construct(protected User $model)
    {
    }

    public function update($attributes){
        $data = [];
        $data['email'] = $attributes['email'];
        $data['phone'] = preg_replace("/[^0-9]/", "", $attributes['phone']);
        $data['middle_name'] = $attributes['middle_name'];
        $data['address_invoice'] = $attributes['street'];
        $data['house_number'] = $attributes['house_number'];
        $data['room'] = $attributes['room'];
        $data['s_name'] = $attributes['surname'];
        $data['name'] = $attributes['name'];

        $this->model->query()->where('id','=',Auth::user()->id)
            ->update($data);
    }



    public function updateMerchant($attributes,$id){
        $data = [];
        $data['shop_name'] = $attributes['shop_name'];
        $data['reg_form'] = $attributes['reg_form'] ?? '';
        $data['vkn'] = $attributes['vkn'];
        $data['iban'] = $attributes['iban'];
        $data['tckn'] = $attributes['tckn'];
        $data['address_ur'] = $attributes['address_ur'] ?? null;
        $data['type_invoice'] = $attributes['type_invoice'] ?? null;
        $data['address_invoice'] = $attributes['address_invoice'] ?? null;
        $data['address_return'] = $attributes['address_return'] ?? null;
        $data['email'] = $attributes['email'];

        $data['shot_name'] = $attributes['company_name'];

        $data['name'] = $attributes['first_name'];
        $data['s_name'] = $attributes['last_name'];
        $data['middle_name'] = $attributes['patronymic'];

        $data['country_id'] = $attributes['country_id'] ?? null;
        $data['region_id'] = $attributes['region_id'] ?? null;
        $data['area_id'] = $attributes['area_id'] ?? null;
        $data['city_id'] = $attributes['city_id'] ?? null;
        $data['city_title'] = $attributes['city_title'] ?? null;
        $data['status'] = 1;

        $data['reason'] = $attributes['reason'] ?? null;

        if (!empty($attributes['active'])){
            $data['active'] = $attributes['active'];
        }

        if (!empty($attributes['status'])){
            $data['status'] = $attributes['status'];
        }

        if (!empty($attributes['status']) && $attributes['status'] == 2){
            $data['active'] = 'Y';
        }

        $this->model->query()->where('id','=',$id)
            ->update($data);
    }
}
