<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    use AjaxFromRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        header('Content-Type:application/json; charset=UTF-8');
        return [
            'address_invoice'            => 'required',
            'house_number'            => 'required',
            'gender'            => 'required',
            'country_id'            => 'required',
            'region_id'            => 'required',
            'area_id'            => 'required',
            'city_id'            => 'required',
            'email'            => 'required',
            'name'            => 'required',
            's_name'            => 'required',
            'postcode_id'            => 'required',
            'to_cart'            => '',
        ];
    }



    public function messages()
    {
        return [
            'address_invoice.required' => trans('system.qq16') .' '. trans('customer.form.address_invoice'),
            'house_number.required' => trans('system.qq16') .' '. trans('customer.form.house_number'),
            'room.required' => trans('system.qq16') .' '. trans('customer.form.room'),
            'birthday.required' => trans('system.qq16') .' '. trans('customer.form.birthday'),
            'whatsapp.required' => trans('system.qq16') .' '. trans('customer.form.whatsapp'),
            'telegram.required' => trans('system.qq16') .' '. trans('customer.form.telegram'),
            'gender.required' => trans('system.qq16') .' '. trans('customer.form.gender'),
            'country_id.required' => trans('system.qq16') .' '. trans('customer.form.country_id'),
            'region_id.required' => trans('system.qq16') .' '. trans('customer.form.region_id'),
            'area_id.required' => trans('system.qq16') .' '. trans('customer.form.area_id'),
            'city_id.required' => trans('system.qq16') .' '. trans('customer.form.city_id'),
            'country_title.required' => trans('system.qq16') .' '. trans('customer.form.country_title'),
            'region_title.required' => trans('system.qq16') .' '. trans('customer.form.region_title'),
            'area_title.required' => trans('system.qq16') .' '. trans('customer.form.area_title'),
            'city_title.required' => trans('system.qq16') .' '. trans('customer.form.city_title'),
            'phone.required' => trans('system.qq16') .' '. trans('customer.form.phone'),
            'email.required' => trans('system.qq16') .' '. trans('customer.form.email'),
            'name.required' => trans('system.qq16') .' '. trans('customer.form.first_name'),
            'm_name.required' => trans('system.qq16') .' '. trans('customer.form.m_name'),
            's_name.required' => trans('system.qq16') .' '. trans('customer.form.s_name'),
            'postcode_id.required' => trans('system.qq16') .' '. trans('customer.form.postcode_id'),
            'postcode.required' => trans('system.qq16') .' '. trans('customer.form.postcode'),
        ];
    }
}
