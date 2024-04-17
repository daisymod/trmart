<?php

namespace App\Http\Controllers;

use App\Forms\ExtendSliderForm;
use App\Forms\MerchantAdminForm;
use App\Forms\MerchantRegForm;
use App\Forms\SliderAdminForm;
use App\Http\Requests\MerchantEditPostRequest;
use App\Http\Requests\MerchantRegPostRequest;
use App\Http\Requests\UserRegFromPostRequest;
use App\Http\Requests\UserRegSMSPostRequest;
use App\Models\Merchant;
use App\Models\Slider;
use App\Services\UserLoginService;
use Illuminate\Support\Facades\Gate;

class SliderController extends Controller
{
    public function actionList()
    {
        Gate::authorize("slider-list");
        $records = Slider::query()->paginate(50);
        return view("slider.list", compact("records"));
    }

    public function actionAddGet()
    {
        Gate::authorize("slider-add");
        $form = new ExtendSliderForm(new Slider());
        $form = $form->formRenderAdd();
        return view("slider.edit", compact( "form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("slider-add");
        $record = new ExtendSliderForm(new Slider());
        $record->formSave(request()->all());
        return ["redirect" => route("slider.list")];
    }

    public function actionEditGet($id)
    {
        $record = Slider::query()->findOrFail($id);
        Gate::authorize("slider-edit", $record);
        $form = new ExtendSliderForm($record);
        $form = $form->formRenderEdit();
        return view("slider.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = Slider::query()->findOrFail($id);
        Gate::authorize("slider-edit", $record);
        $form = new ExtendSliderForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("slider.list")];
    }

    public function actionDel($id)
    {
        $record = Slider::query()->findOrFail($id);
        Gate::authorize("slider-del", $record);
        $record->delete();
        return redirect(route("slider.list"));
    }
}

