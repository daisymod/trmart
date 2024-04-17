<?php

namespace App\Http\Controllers;

use App\Forms\ShippingRateAdminForm;
use App\Models\ShippingMethod;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\Gate;

class ShippingRateController extends Controller
{
    public function actionList($id)
    {
        Gate::authorize("shipping-rate-list");
        $record = ShippingMethod::query()->findOrFail($id);
        $records = ShippingRate::query()->where("shipping_method_id", $id)->paginate(50);
        return view("shipping_rate.list", compact("records", "id", "record"));
    }

    public function actionAddGet($id)
    {
        Gate::authorize("shipping-rate-add");
        $record = new ShippingRate();
        $record->shipping_method_id = $id;
        $form = new ShippingRateAdminForm($record);
        $form = $form->formRenderAdd();
        return view("shipping_rate.edit", compact( "form", "id"));
    }

    public function actionAddPost()
    {
        Gate::authorize("shipping-rate-add");
        $form = new ShippingRateAdminForm(new ShippingRate());
        $form->formSave(request()->all());
        return ["redirect" => route("shipping_rate.list", $form->getAttribute("shipping_method_id"))];
    }

    public function actionEditGet($id)
    {
        $record = ShippingRate::query()->findOrFail($id);
        Gate::authorize("shipping-rate-edit", $record);
        $form = new ShippingRateAdminForm($record);
        $form = $form->formRenderEdit();
        return view("shipping_rate.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = ShippingRate::query()->findOrFail($id);
        Gate::authorize("shipping-rate-edit", $record);
        $form = new ShippingRateAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("shipping_rate.list", $form->getAttribute("shipping_method_id"))];
    }

    public function actionDel($id)
    {
        $record = ShippingRate::query()->findOrFail($id);
        Gate::authorize("shipping-rate-del", $record);
        $record->delete();
        return redirect(route("shipping_rate.list"));
    }
}
