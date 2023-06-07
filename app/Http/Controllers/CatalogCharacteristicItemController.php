<?php

namespace App\Http\Controllers;

use App\Models\CatalogCharacteristicItem;
use App\Services\CatalogCharacteristicItemActionService;
use Illuminate\Support\Facades\Gate;

class CatalogCharacteristicItemController
{
    public function actionList($parent, CatalogCharacteristicItemActionService $service)
    {
        Gate::authorize("catalog-characteristic-item-list");
        return view("catalog_characteristic_item.list", $service->actionList($parent));
    }

    public function actionAddGet(CatalogCharacteristicItemActionService $service)
    {
        Gate::authorize("catalog-characteristic-item-add");
        return view("catalog_characteristic_item.edit", $service->actionAddGet(request("characteristic", 0)));
    }

    public function actionAddPost(CatalogCharacteristicItemActionService $service)
    {
        Gate::authorize("catalog-characteristic-item-add");
        $form = $service->actionAddPost();
        return ["redirect" => route("catalog_characteristic_item.list", $form->getAttribute("catalog_characteristic_id"))];
    }

    public function actionEditGet($id, CatalogCharacteristicItemActionService $service)
    {
        $record = CatalogCharacteristicItem::query()->findOrFail($id);
        Gate::authorize("catalog-characteristic-item-edit", $record);
        return view("catalog_characteristic_item.edit", $service->actionEditGet($record));
    }

    public function actionEditPost($id, CatalogCharacteristicItemActionService $service)
    {
        $record = CatalogCharacteristicItem::query()->findOrFail($id);
        Gate::authorize("catalog-characteristic-item-edit", $record);
        $form = $service->actionEditPost($record);
        return ["redirect" => route("catalog_characteristic_item.list", $form->getAttribute("catalog_characteristic_id"))];
    }

    public function actionDel($id)
    {
        $record = CatalogCharacteristicItem::query()->findOrFail($id);
        Gate::authorize("catalog-characteristic-item-del", $record);
        $id = $record->getAttribute("catalog_characteristic_id");
        $record->delete();
        return redirect(route("catalog_characteristic_item.list", $id));
    }

    public function actionDND()
    {
        CatalogCharacteristicItem::dragAndDrop(request("row", []));
    }
}
