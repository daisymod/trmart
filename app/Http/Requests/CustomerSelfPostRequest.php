<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CustomerSelfPostRequest extends FormRequest
{
    use AjaxFromRequestTrait;

    public function rules()
    {
        $rules = [
            "name" => "required|max:255",
            "phone" => "required|max:255|unique:users,phone," . Auth::user()->id,
        ];
        if (!empty(request("email"))) {
            $rules["email"] = "max:255|unique:users,email," . Auth::user()->id;
        }
        return $rules;
    }


    public function messages()
    {
        return [
            'name.required' => trans('system.qq16') .' '. trans('body.first_name'),
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
        ];
    }
}
