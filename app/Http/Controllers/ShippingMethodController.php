<?php

namespace App\Http\Controllers;

use App\Forms\ShippingMethodAdminForm;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Gate;

class ShippingMethodController extends Controller
{
    public function actionList()
    {
        Gate::authorize("shipping-method-list");
        $records = ShippingMethod::query()->paginate(50);
        return view("shipping_method.list", compact("records"));
    }

    public function actionAddGet()
    {
        Gate::authorize("shipping-method-add");
        $form = new ShippingMethodAdminForm(new ShippingMethod());
        $form = $form->formRenderAdd();
        return view("shipping_method.edit", compact( "form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("shipping-method-add");
        $record = new ShippingMethodAdminForm(new ShippingMethod());
        $record->formSave(request()->all());
        return ["redirect" => route("shipping_method.list")];
    }

    public function actionEditGet($id)
    {
        $record = ShippingMethod::query()->findOrFail($id);
        Gate::authorize("shipping-method-edit", $record);
        $form = new ShippingMethodAdminForm($record);
        $form = $form->formRenderEdit();
        return view("shipping_method.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = ShippingMethod::query()->findOrFail($id);
        Gate::authorize("shipping-method-edit", $record);
        $form = new ShippingMethodAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("shipping_method.list")];
    }

    public function actionDel($id)
    {
        $record = ShippingMethod::query()->findOrFail($id);
        Gate::authorize("shipping-method-del", $record);
        $record->delete();
        return redirect(route("shipping_method.list"));
    }
}
