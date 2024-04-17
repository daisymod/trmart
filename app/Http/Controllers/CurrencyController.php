<?php

namespace App\Http\Controllers;

use App\Forms\CurrencyAdminForm;
use App\Models\Currency;
use App\Services\CurrencyService;
use App\Services\RatesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CurrencyController extends Controller
{

    public function __construct(protected CurrencyService $service,protected RatesService $rates)
    {
    }

    public function actionList()
    {
        Gate::authorize("currency-list");
        $records = Currency::query()->paginate(50);
        return view("currency.list", compact("records"));
    }

    public function actionAddGet()
    {
        Gate::authorize("currency-add");
        $form = new CurrencyAdminForm(new Currency());
        $form = $form->formRenderAdd();
        return view("currency.edit", compact( "form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("currency-add");
        $form = new CurrencyAdminForm(new Currency());
        $form->formSave(request()->all());
        return ["redirect" => route("currency.list")];
    }

    public function actionEditGet($id)
    {
        $record = Currency::query()->findOrFail($id);
        Gate::authorize("currency-edit", $record);
        $form = new CurrencyAdminForm($record);
        $form = $form->formRenderEdit();
        return view("currency.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = Currency::query()->findOrFail($id);
        Gate::authorize("currency-edit", $record);
        $form = new CurrencyAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("currency.list")];
    }

    public function actionDel($id)
    {
        $record = Currency::query()->findOrFail($id);
        Gate::authorize("currency-del", $record);
        $record->delete();
        return redirect(route("currency.list"));
    }


    public function getCoefficient(Request $request){
        $currency = $this->service->getCurrencyById($request->currency_id);
        $turkeyCurrency = $this->service->getTurkeyCurrency();
        $coefficient =  $this->rates->getRateTurkey($currency->id);
        return ['data' => $coefficient,'symbol' => $currency->symbol ];
    }


}

