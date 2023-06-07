<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPrice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeliveryController extends Controller
{
    public function actionDelivery()
    {
        Gate::authorize("delivery-index");
        $items = DeliveryPrice::query()->where('id', 1)->get();
        return view("delivery.index", compact('items'));
    }

    public function actionPostDelivery(Request $request)
    {
        Gate::authorize("delivery-save");
        $model = DeliveryPrice::query()->find( $request->input('id'));
        $model->kg_price =  $request->input('d_sum');
        $model->gr_price =  $request->input('d_sum') / 1000;
        $model->save();

        return redirect('/delivery/index');
    }
}
