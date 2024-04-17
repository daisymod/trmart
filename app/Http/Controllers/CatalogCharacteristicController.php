<?php

namespace App\Http\Controllers;

use App\Forms\CatalogCharacteristicAdminForm;
use App\Forms\ExtendCatalogCharacteristicAdminForm;
use App\Models\CatalogCharacteristic;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class CatalogCharacteristicController
{
    public function actionList()
    {
        Gate::authorize("catalog-characteristic-list");
        $records = CatalogCharacteristic::query()
            ->orderBy("position", "asc")
            ->paginate(50);
        $columns = Schema::getColumnListing("catalog_items");
        return view("catalog_characteristic.list", compact("records", "columns"));
    }

    public function actionAddGet()
    {
        Gate::authorize("catalog-characteristic-add");
        $form = new ExtendCatalogCharacteristicAdminForm(new CatalogCharacteristic());
        $form = $form->formRenderAdd();
        return view("catalog_characteristic.edit", compact( "form"));
    }

    public function actionAddPost()
    {
        Gate::authorize("catalog-characteristic-add");
        $record = new ExtendCatalogCharacteristicAdminForm(new CatalogCharacteristic());
        $record->formSave(request()->all());
        return ["redirect" => route("catalog_characteristic.list")];
    }

    public function actionEditGet($id)
    {
        $record = CatalogCharacteristic::query()->findOrFail($id);
        Gate::authorize("catalog-characteristic-edit", $record);
        $form = new ExtendCatalogCharacteristicAdminForm($record);
        $form = $form->formRenderEdit();
        return view("catalog_characteristic.edit", compact("record", "form"));
    }

    public function actionEditPost($id)
    {
        $record = CatalogCharacteristic::query()->findOrFail($id);
        Gate::authorize("catalog-characteristic-edit", $record);
        $form = new ExtendCatalogCharacteristicAdminForm($record);
        $form->formSave(request()->all());
        return ["redirect" => route("catalog_characteristic.list")];
    }

    public function actionDel($id)
    {
        $record = CatalogCharacteristic::query()->findOrFail($id);
        Gate::authorize("catalog-characteristic-del", $record);
        $record->delete();
        return redirect(route("catalog_characteristic.list"));
    }

    public function actionDND()
    {
        CatalogCharacteristic::dragAndDrop(request("row", []));
    }
}
