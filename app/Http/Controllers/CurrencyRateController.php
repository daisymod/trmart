<?php

namespace App\Http\Controllers;


use App\Forms\CurrencyRateAdminForm;
use App\Models\Currency;
use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Gate;

class CurrencyRateController
{
    public function actionList($id)
    {
        Gate::authorize("currency-rate-list");
        $record = Currency::query()->findOrFail($id);
        $records = CurrencyRate::query()
            ->where("currency_id", $id)
            ->paginate(50);
        return view("currency_rate.list", compact("records", "id", "record"));
    }

    public function actionAddGet($id)
    {
        Gate::authorize("currency-rate-add");
        $record = new CurrencyRate();
        $record->currency_id = $id;
        $form = new CurrencyRateAdminForm($record);
        $form = $form->formRenderAdd();
        return view("currency_rate.edit", compact("form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("currency-rate-add");
        $form = new CurrencyRateAdminForm(new CurrencyRate());
        $form->formSave(request()->all());
        return ["redirect" => route("currency_rate.list", $form->getAttribute("currency_id"))];
    }

    public function actionEditGet($id)
    {
        $record = CurrencyRate::query()->findOrFail($id);
        Gate::authorize("currency-rate-edit", $record);
        $form = new CurrencyRateAdminForm($record);
        $form = $form->formRenderEdit();
        return view("currency_rate.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = CurrencyRate::query()->findOrFail($id);
        Gate::authorize("currency-rate-edit", $record);
        $form = new CurrencyRateAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("currency_rate.list", $form->getAttribute("currency_id"))];
    }

    public function actionDel($id)
    {
        $record = CurrencyRate::query()->findOrFail($id);
        Gate::authorize("currency-rate-del", $record);
        $id = $record->currency_id;
        $record->delete();
        return redirect(route("currency_rate.list", $id));
    }

}
