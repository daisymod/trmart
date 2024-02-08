<?php

namespace App\Actions;

use App\Models\CatalogItem;
use App\Requests\GptRequest;
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
            elseif (str_contains($action, "gpt")) {
                $request = new GptRequest();
                $dataNameKz = $request->getData($record->name_tr,'казахский');
                if (isset($dataNameKz['data']['choices'][0]['message']['content'])){
                    $record->name_kz = $dataNameKz['data']['choices'][0]['message']['content'];
                }

                $dataNameRu = $request->getData($record->name_tr,'русский');
                if (isset($dataNameRu['data']['choices'][0]['message']['content'])){
                    $record->name_ru = $dataNameRu['data']['choices'][0]['message']['content'];
                }


                if (!empty($record->body_tr)){
                    $dataBodyRu = $request->getData($record->body_tr,'русский');
                    $dataBodyKz = $request->getData($record->body_tr,'казахский');
                    if (isset($dataBodyKz['data']['choices'][0]['message']['content'])){
                        $record->body_kz = $dataBodyKz['data']['choices'][0]['message']['content'];
                    }
                    if (isset($dataBodyRu['data']['choices'][0]['message']['content'])){
                        $record->body_ru = $dataBodyRu['data']['choices'][0]['message']['content'];
                    }
                }

                CatalogItem::query()->where('id','=',$record->id)
                    ->update([
                        'name_ru' => $record->name_ru,
                        'name_kz' => $record->name_kz,
                        'body_ru' => $record->body_ru,
                        'body_kz' => $record->body_kz,

                    ]);

            }
        }

        return redirect(route("catalog_item.list",
            [
                'name'=> request()->get('name'),
                'brand'=> request()->get('brand'),
                'article'=> request()->get('article'),
                'status'=> request()->get('status'),
                'active'=> request()->get('active'),
                'barcode'=> request()->get('barcode'),
                'price_from'=> request()->get('price_from'),
                'price_to'=> request()->get('price_to'),
                'catalog[]'=> request()->get('catalog'),
                'user[]'=> request()->get('user'),
            ]
        ))->send();
    }
}
