<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class LogistOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'price'             => number_format($this->price, 2, '.', ' '),
            'status'            => $this->status,
            'barcode'           => $this->barcode,
            'article'           => $this->article,
            'merchant'          => $this->merchant,
            'city_name'         => $this->city_name,
            'created_at'        => date('d.m.Y H:m', strtotime($this->created_at)),
            'real_weight'       => $this->real_weight,
            'country_name'      => $this->country_name,
            'delivery_price'    => number_format($this->delivery_price, 2, '.', ' '),
            'tr_delivery_price' => number_format($this->tr_delivery_price, 2, '.', ' '),
            'total_delivery_price'    => number_format($this->delivery_price + $this->tr_delivery_price, 2, '.', ' '),
            'delivery_kz_weighing'    => number_format($this->delivery_kz_weighing, 2, '.', ' '),
            'delivery_tr_weighing' => number_format($this->delivery_tr_weighing, 2, '.', ' '),
        ];
    }
}
