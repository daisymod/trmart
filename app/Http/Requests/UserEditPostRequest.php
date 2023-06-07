<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UserEditPostRequest extends FormRequest
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
            "name" => "required|max:255",
            "phone" => "required|max:255|unique:users,phone," . request("id"),
        ];
    }


    public function messages()
    {
        return [
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
            'name.required' => trans('system.qq16') .' '. trans('body.name'),
        ];
    }
}
