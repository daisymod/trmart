<?php

namespace App\Http\Controllers;

use App\Fields\ShippingMethodField;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FieldShippingMethodController
{
    protected ShippingMethodField|string $field = ShippingMethodField::class;

    public function __invoke(Request $request, string $action)
    {
        $action = "action" . Str::ucfirst(Str::camel($action)) . Str::ucfirst(strtolower($request->method()));
        if (method_exists($this, $action)) {
            return $this->{$action}();
        } else {
            abort(404, $action);
        }
    }

    public function actionFindGet()
    {
        Gate::authorize("shipping-method-list");
        $field = new $this->field;
        return $field->getFindHtml();
    }

    public function actionListGet()
    {
        Gate::authorize("shipping-method-list");
        $field = new $this->field;
        return $field->actionList(request()->all());
    }

/*    public function actionAddGet()
    {
        Gate::authorize("merchant-add");
        $form = new SliderEditForm(new Merchant());
        $form = $form->formRenderAdd();
        return view("slider.edit", compact("form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("merchant-add");
        $record = new SliderEditForm(new Merchant());
        $record->formSave(request()->all());
        return ["redirect" => route("slider.list")];
    }

    public function actionEditGet($id)
    {
        $record = Merchant::query()->findOrFail($id);
        Gate::authorize("merchant-edit", $record);
        $form = new SliderEditForm($record);
        $form = $form->formRenderEdit();
        return view("slider.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = Merchant::query()->findOrFail($id);
        Gate::authorize("merchant-edit", $record);
        $form = new SliderEditForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("slider.list")];
    }*/
}
