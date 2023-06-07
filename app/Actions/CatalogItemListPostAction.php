<?php

namespace App\Actions;

use App\Models\CatalogItem;
use Illuminate\Support\Facades\Gate;

class CatalogItemListPostAction
{
    public function __invoke($ids, $action)
    {
        foreach ($ids as $id) {
            $record = CatalogItem::query()->findOrFail($id);
            if ($action == "delete") {
                Gate::authorize("catalog-item-del", $record);
                $record->delete();
            } elseif ($action == "verification") {
                Gate::authorize("catalog-item-edit", $record);
                $record->status = 1;
                $record->save();
            } elseif (str_contains($action, "active_")) {
                Gate::authorize("catalog-item-edit", $record);
                $active = "Y";
                if ($action == "active_n") {
                    $active = "N";
                }
                $record->active = $active;
                $record->save();
            } elseif (str_contains($action, "status_")) {
                Gate::authorize("catalog-item-status", $record);
                $status = 2;
                $record->status = $status;
                $record->save();
            }
        }

        return redirect(route("catalog_item.list"))->send();
    }
}
