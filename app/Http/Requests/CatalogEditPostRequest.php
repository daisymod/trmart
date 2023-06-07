<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CatalogEditPostRequest extends FormRequest
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
            "name_ru" => "required|max:255",
            //"commission" => "required",
        ];
    }


    public function messages()
    {
        return [
            'name_ru.required' => trans('system.qq16') .' '. trans('catalog_characteristic_item.form.name_ru'),
        ];
    }
}
