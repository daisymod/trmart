<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegFromPostRequest extends FormRequest
{
    use AjaxFromRequestTrait;

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
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => "required|max:255",
            "phone" => "required|max:255|unique:users",
            "password" => "required|min:6|max:255|confirmed",
        ];
    }


    public function messages()
    {
        return [
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
            'pass.required' => trans('system.qq16') .' '. trans('body.password'),
            'name.required' => trans('system.qq16') .' '. trans('body.name'),
        ];
    }
}
