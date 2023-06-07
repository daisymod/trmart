<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class MerchantRegPostRequest extends FormRequest
{
    use AjaxFromRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "first_name" => "required|max:255",
            "last_name" => "required|max:255",
            "phone" => "required|max:255|unique:users,phone",
            //"address" => "required|max:255",
            "password" => "required|max:255|confirmed",
        ];
    }


    public function messages()
    {
        return [
            'first_name.required' => trans('system.qq16') .' '. trans('body.first_name'),
            'last_name.required' => trans('system.qq16') .' '. trans('body.last_name'),
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
            'password.required' => trans('system.qq16') .' '. trans('body.password'),
        ];
    }
}
