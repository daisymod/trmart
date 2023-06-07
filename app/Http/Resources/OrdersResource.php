<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrdersResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {

        return [
            'delivery_dt_start' => date('d.m.Y H:m', strtotime($this->delivery_dt_start)),
            'delivery_dt_end'   => date('d.m.Y', strtotime($this->delivery_dt_end)),
            'left_status'       => $this->leftStatus($this->delivery_dt_end),
            'status'            => $this->status,
            'price'             => number_format($this->price, 2, '.', ' '),
            'left'              => $this->leftData($this->delivery_dt_end),
            'id'                => $this->id,
            'sale'        => 	$this->sale,
            'created_at'        => $this->created_at,
        ];
    }
    private function leftData($date): string
    {
        $remainingDays = Carbon::now()->format('Y-m-d') >= Carbon::parse($date)
            ? 'end'
            : Carbon::parse($date)->diff(Carbon::now()->format('Y-m-d'))->format('%d');

        switch ($remainingDays) {
            case 'end':
                return trans('system.statusEnd');
            case 0:
                return trans('system.today');
            case 1:
                return $remainingDays.' '.trans('system.day');
            case in_array($remainingDays, range(2,4)):
                return $remainingDays.' '.trans('system.day1');
            default:
                return $remainingDays.' '.trans('system.day2');
        }

    }
    private function leftStatus($date): string
    {
        return Carbon::now()->format('Y-m-d') >= Carbon::parse($date) ? 0 :1;
    }
}
