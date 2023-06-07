<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserRegSMSPostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "sms" => "required",
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (request("sms") <> session()->get("regDataSMS")) {
                $validator->errors()->add("sms", trans('system.codeNotFound'));
            }
        });
    }


    public function messages()
    {
        return [
            'sms.required' => trans('system.qq16') .' '. "sms",
        ];
    }
}
