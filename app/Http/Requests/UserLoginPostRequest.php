<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserLoginPostRequest extends FormRequest
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
            "phone" => "required|max:255",
            "pass" => "required|max:255",
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::getOnPhone(request("phone"));
            if (empty($user)) {
                $validator->errors()->add("phone", "Пользователь с таким номером телефона не найден.");
            } else{
                $validator->errors()->add("pass", "Не удаётся войти. Пожалуйста, проверьте правильность написания номера телефона и пароля.");
            }
        });
        /*
         *  elseif (!Hash::check(request("pass"), $user->password)) {
                $validator->errors()->add("pass", "Не удаётся войти. Пожалуйста, проверьте правильность написания номера телефона и пароля.");
            }
         * elseif ($user->active <> "Y" && ($user->role != 'admin') )  {
                $validator->errors()->add("phone", "Ваш Аккаунт заблокирован Администратором проекта.");
            }*/
    }

    public function messages()
    {
        return [
            'phone.required' => trans('system.qq16') .' '. trans('system.phone'),
            'pass.required' => trans('system.qq16') .' '. trans('body.password'),
        ];
    }
}
