<?php

namespace App\Http\Controllers;

use App\Forms\PageAdminForm;
use App\Models\Page;
use Illuminate\Support\Facades\Gate;

class PageController extends Controller
{
    public function actionList()
    {
        Gate::authorize("page-list");
        $records = Page::query()->paginate(50);
        return view("page.list", compact("records"));
    }

    public function actionAddGet()
    {
        Gate::authorize("page-add");
        $form = new PageAdminForm(new Page());
        $form = $form->formRenderAdd();
        return view("page.edit", compact("form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("page-add");
        $form = new PageAdminForm(new Page());
        $form->formSave(request()->all());
        return ["redirect" => route("page.list")];
    }

    public function actionEditGet($id)
    {
        $record = Page::query()->findOrFail($id);
        Gate::authorize("page-edit", $record);
        $form = new PageAdminForm($record);
        $form = $form->formRenderEdit();
        return view("page.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = Page::query()->findOrFail($id);
        Gate::authorize("page-edit", $record);
        $form = new PageAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("page.list")];
    }

    public function actionDel($id)
    {
        $record = Page::query()->findOrFail($id);
        Gate::authorize("page-del", $record);
        $record->delete();
        return redirect(route("page.list"));
    }

    public function actionUrl($url)
    {
        $record = Page::query()->where("url", $url)->firstOrFail();
        return view("page.url", compact("record"));
    }
}

