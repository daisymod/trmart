<?php

namespace App\Http\Controllers;

use App\Clients\KazPost;
use App\Models\DeliveryPrice;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculateController extends Controller
{
    public function getCalculateDelivery(Request $request): JsonResponse
    {
        $weight = $request->input('weight');
        $order = Order::query()->find($request->input('id'));
        $client = new KazPost();
        $kps = $client->getPostRate($weight, $order->price, $order->postcode);
        $td  = DeliveryPrice::query()->where('id', 1)->value('gr_price');
        if (isset($kps->Sum)) {
            $delivery_price = doubleval($kps->Sum);
            $order->real_weight = $weight;
            $order->delivery_kz_weighing = $delivery_price;
            $order->delivery_tr_weighing = doubleval($weight) * $td;
            $order->save();
            return response()->json(
                [
                    number_format($order->delivery_kz_weighing, 2, '.', ' '),
                    number_format($order->delivery_tr_weighing, 2, '.', ' ')
                ], 201);
        }

        return response()->json(["name" => ['Каз Почта: '.$kps->ResponseInfo->ResponseText]], 422);
    }
}
