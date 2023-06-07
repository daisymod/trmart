<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CartOderPostRequest extends FormRequest
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
            "name" => "required|max:255",
            "phone" => "required",
            "email" => "required|max:255",
            "surname" => "required|max:255",
            "postcode_id" => "required|max:255",


            //"commission" => "required",
        ];
    }


    public function messages()
    {
        return [
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
            'name.required' => trans('system.qq16') .' '. trans('body.first_name'),
            'email.required' => trans('system.qq16') .' '. "email",
            'surname.required' => trans('system.qq16') .' '. trans('body.last_name'),
            'postcode.required' => trans('system.qq16') .' '. trans('customer.form.postcode')
        ];
    }

}
