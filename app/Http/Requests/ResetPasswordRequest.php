<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "phone" => "",
            "password" => "required|max:255|confirmed",
        ];
    }


    public function messages()
    {
        return [
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
            'password.required' => trans('system.qq16') .' '. trans('body.password'),
        ];
    }
}
