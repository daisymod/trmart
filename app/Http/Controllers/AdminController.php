<?php

namespace App\Http\Controllers;

use App\Forms\AdminAdminForm;
use App\Forms\NewsAdminForm;
use App\Forms\UserAdminForm;
use App\Http\Requests\MerchantEditPostRequest;
use App\Http\Requests\UserEditPostRequest;
use App\Http\Requests\UserLoginPostRequest;
use App\Http\Requests\UserRegFromPostRequest;
use App\Http\Requests\UserRegSMSPostRequest;
use App\Models\CatalogItem;
use App\Models\Merchant;
use App\Models\News;
use App\Models\User;

use Illuminate\Support\Facades\Gate;

class AdminController
{
    public function actionList()
    {
        Gate::authorize("admin-list");
        $records = User::query()
            ->where("role", "admin")
            ->paginate(50);
        return view("admin.list", compact("records"));
    }

    public function actionAddGet()
    {
        Gate::authorize("admin-add");
        $form = new AdminAdminForm(new User());
        $form = $form->formRenderAdd();
        return view("admin.edit", compact( "form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("admin-add");
        $record = new AdminAdminForm(new User());
        $record->formSave(request()->all());
        return ["redirect" => route("admin.list")];
    }

    public function actionEditGet($id)
    {
        $record = User::query()->findOrFail($id);
        Gate::authorize("admin-edit", $record);
        $form = new AdminAdminForm($record);
        $form = $form->formRenderEdit();
        return view("admin.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = User::query()->findOrFail($id);
        Gate::authorize("admin-edit", $record);
        $form = new AdminAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("admin.list")];
    }

    public function actionDel($id)
    {
        $record = User::query()->findOrFail($id);
        Gate::authorize("admin-del", $record);
        $record->delete();
        return redirect(route("admin.list"));
    }
}
