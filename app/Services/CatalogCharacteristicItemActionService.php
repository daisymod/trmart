<?php

namespace App\Services;

use App\Forms\CatalogCharacteristicItemAdminForm;
use App\Forms\CatalogCharacteristicItemMerchantForm;
use App\Models\CatalogCharacteristicItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CatalogCharacteristicItemActionService
{
    public function actionList($parent)
    {
        $records = CatalogCharacteristicItem::query()
            ->where("catalog_characteristic_id", $parent)
            ->orderBy("position")
            ->get();
        return compact("records", "parent");
    }

    public function actionAddGet($parent = 0)
    {
        $record = new CatalogCharacteristicItem();
        $record->catalog_characteristic_id = $parent;
        $form = new CatalogCharacteristicItemAdminForm($record);
        $form = $form->formRenderAdd();
        return compact("form");
    }

    public function actionAddPost()
    {
        $form = new CatalogCharacteristicItemAdminForm(new CatalogCharacteristicItem());
        return $form->formSave(request()->all());
    }

    public function actionEditGet($record)
    {
        $form = new CatalogCharacteristicItemAdminForm($record);
        $form = $form->formRenderEdit();
        return compact("record", "form");
    }

    public function actionEditPost($record)
    {
        $form = new CatalogCharacteristicItemAdminForm($record);
        return $form->formSave(request()->all());
    }
}
