<?php

namespace App\Http\Controllers;

use App\Forms\ExtendNewsAdminForm;
use App\Forms\NewsAdminForm;
use App\Models\News;
use App\Models\Slider;
use Illuminate\Support\Facades\Gate;

class NewsController
{
    public function actionList()
    {
        Gate::authorize("news-list");
        $records = News::query()
            ->orderBy("dt", "desc")
            ->paginate(50);
        return view("news.list", compact("records"));
    }

    public function actionAddGet()
    {
        Gate::authorize("news-add");
        $form = new ExtendNewsAdminForm(new News());
        $form = $form->formRenderAdd();
        return view("news.edit", compact( "form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("news-add");
        $record = new ExtendNewsAdminForm(new News());
        $record->formSave(request()->all());
        return ["redirect" => route("news.list")];
    }

    public function actionEditGet($id)
    {
        $record = News::query()->findOrFail($id);
        Gate::authorize("news-edit", $record);
        $form = new ExtendNewsAdminForm($record);
        $form = $form->formRenderEdit();
        return view("news.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = News::query()->findOrFail($id);
        Gate::authorize("news-edit", $record);
        $form = new ExtendNewsAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("news.list")];
    }

    public function actionDel($id)
    {
        $record = News::query()->findOrFail($id);
        Gate::authorize("news-del", $record);
        $record->delete();
        return redirect(route("news.list"));
    }
}
