<?php

namespace App\Http\Requests;

use App\Traits\AjaxFromRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CatalogItemEditPostRequest extends FormRequest
{
    use AjaxFromRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            "weight" => "required",
            "price" => "required",
            "brand" => "required",
            "image" => "required",
            "catalog" => "required",
        ];


        if (empty((request('name')['ru']))) {

            $rules["name_ru"] = "required";
        }

        if (empty(request('name')['kz'])) {
            $rules["name_kz"] = "required";
        }

        if (empty(request('name')['tr'])) {
            $rules["name_tr"] = "required";
        }


        if (!empty(request("status")) AND request("status") == 3) {
            $rules["reason"] = "required";
        }

        if (Auth::user()->role == 'admin') {
            $rules["user"] = "required";
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!isset(request('addmore')[0]['image']) && !isset(request('addmore')[0]['0']) ) {
                //$validator->errors()->add("image", "Необходимо фото для галереи");
            }
        });
    }

    public function messages()
    {
        return [
            'name.required' => trans('system.qq16') .' '. trans('body.first_name'),
            'weight.required' => trans('system.qq16') .' '. trans('catalog_item.form.weight'),
            'price.required' => trans('system.qq16') .' '. trans('system.price'),
            'brand.required' => trans('system.qq16') .' '. trans('system.brand'),
            'image.required' => trans('system.qq16') .' '. trans('system.images'),
            'catalog.required' => trans('system.qq16') .' '. trans('catalog_item.form.catalog'),
            'user.required' => trans('system.qq16') .' '. trans('catalog_item.form.user'),
            'addmore.required' => trans('system.qq16') .' '. trans('system.images'),
        ];
    }
}
