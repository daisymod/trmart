<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class OrderInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {

        return [
            'delivery_dt_start' => date('d.m.Y', strtotime($this->delivery_dt_start)),
            'delivery_price'    => number_format($this->delivery_price + $this->tr_delivery_price, 2, '.', ' '),
            'house_number'      => $this->house_number,
            'country_name'      => $this->country_name,
            'city_name'         => $this->city_name,
            'payment'           => number_format($this->payment, 2, '.', ' '),
            'status'            => $this->statuses($this->status),
            'street'            => $this->street,
            'price'             => number_format($this->price, 2, '.', ' '),
            'room'              => $this->room,
            'id'                => $this->id,
            'status_int'        => $this->status,
            'created_at'        => Carbon::parse($this->created_at)->format('Y-m-d'),
            'updated_at'        => Carbon::parse($this->updated_at)->format('Y-m-d'),
            'total' => number_format(($this->delivery_price + $this->tr_delivery_price + $this->price) , 2, '.', ' '),
            'sale'        => 	$this->sale,
            'barcode'        => 	$this->barcode,
        ];
    }

    public function statuses($status)
    {
        switch ($status) {
            case 1:
                return ['0%', '0%'];
            case 2:
                return ['25%', 'calc(25% + 115px)'];
            case 3:
                return ['50%', 'calc(50% + 115px)'];
            case 4:
                return ['100%', '100%'];
        }
    }
}
