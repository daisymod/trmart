<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Requests\KazPostRequest;

class TrackingService
{
    public function __construct(
        private KazPostRequest $kazPostRequest,

    )
    {
    }

    public function tracking(){

        $actualOrders = Order
            ::where('created_at', '>' ,Carbon::now()->subDays(20))
            ->orderByDesc('created_at')
            ->get()
            ->whereNotIn('status', [
               1,2,3,4,5
            ]);

        foreach ($actualOrders as $order)
        {
            if( !isset($order->barcode) && $order->created_at < Carbon::now()->subDays(6))
            {

                $order->update(
                    [
                        'status' => 7
                    ]
                );

            }
            $this->kazPostRequest->tracking($order);

        }
    }


}
