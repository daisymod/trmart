<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MerchantSelfPostRequest extends FormRequest
{
    use AjaxFromRequestTrait;

    public function rules()
    {
        $rules = [
            "phone" => "required|max:255|unique:users,phone," . Auth::user()->id,
            "company_name" => "required|",
            "shop_name" => "required|",
            "first_name" => "required|",
            "last_name" => "required|",
            "tax_office" => "required|",
            "legal_address_city" => "required|",
            "legal_address_street" => "required|",

            "legal_address_number" => "required|",
            "city" => "required|",
            "street" => "required|",
            "number" => "required|",
            "city_id" => "required|",

            "sale_category" => "required|",
        ];

        if (empty(request("tckn"))) {
            $rules["vkn"] = "required";
        }

        if (empty(request("vkn"))) {
            $rules["tckn"] = "max:255|";
        }


        if (!empty(request("email"))) {
            $rules["email"] = "max:255";
        }
        return $rules;
    }




    public function messages()
    {
        return [
            'first_name.required' => trans('system.qq16') .' '. trans('body.first_name'),
            'last_name.required' => trans('system.qq16') .' '. trans('body.last_name'),
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
            'company_name.required' => trans('system.qq16') .' '. trans('merchant.form.company_name'),
            'shop_name.required' => trans('system.qq16') .' '. trans('merchant.form.shop_name'),

            'tax_office.required' => trans('system.qq16') .' '. trans('merchant.form.tax_office'),
            'legal_address_city.required' => trans('system.qq16') .' '. trans('merchant.form.legal_address_city'),
            'legal_address_street.required' => trans('system.qq16') .' '. trans('merchant.form.legal_address_street'),
            'legal_address_number.required' => trans('system.qq16') .' '. trans('merchant.form.legal_address_number'),
            'city.required' => trans('system.qq16') .' '. trans('merchant.form.city'),
            'street.required' => trans('system.qq16') .' '. trans('merchant.form.street'),
            'number.required' => trans('system.qq16') .' '. trans('merchant.form.number'),
            'city_id.required' => trans('system.qq16') .' '. trans('merchant.form.city_id'),

            'vkn.required' => trans('system.qq16') .' '. trans('merchant.form.vkn'),
            'tckn.required' => trans('system.qq16') .' '. trans('merchant.form.tckn'),
            'email.required' => trans('system.qq16') .' '. trans('merchant.form.email'),


            'sale_category.required' => trans('system.qq16') .' '. trans('merchant.form.sale_category'),
        ];
    }
}
