<?php

namespace App\Http\Controllers;

use App\Forms\CatalogAdminForm;
use App\Http\Requests\CatalogEditPostRequest;
use App\Models\Catalog;
use App\Models\CatalogCatalogCharacteristic;
use App\Models\CatalogItem;
use App\Services\BreadcrumbService;
use Illuminate\Support\Facades\Gate;
use JetBrains\PhpStorm\ArrayShape;

class CatalogController
{
    public function actionList($id)
    {
        Gate::authorize("catalog-list");
        $records = Catalog::query()
            ->orderBy("name_ru", "ASC")
            ->where("parent_id", $id)
            ->paginate(50);

        $breadcrumbs = [];

        return view("catalog.list", compact("records", "breadcrumbs", "id"));
    }

    public function actionAddGet($id)
    {
        Gate::authorize("catalog-add");
        $record = new Catalog();
        $record->parent_id = $id;
        $form = new CatalogAdminForm($record);
        $form = $form->formRenderAdd();
        return view("catalog.edit", compact( "form"));
    }

    public function actionAddPost($id, CatalogEditPostRequest $request)
    {
        Gate::authorize("catalog-add");
        $record = new CatalogAdminForm(new Catalog());
        $record->formSave(request()->all());
        return ["redirect" => route("catalog.list", 0)];
    }

    public function actionEditGet($id)
    {
        $record = Catalog::query()->findOrFail($id);
        Gate::authorize("catalog-edit", $record);
        $form = new CatalogAdminForm($record);
        $form = $form->formRenderEdit();
        $active = $record->is_active;

        $array = array();

        array_push($array,intval($id));

        foreach ($record->recursiveChildren as $category){
            array_push($array,$category->id);
            foreach ($category->recursiveChildren as $last){
                array_push($array,$last->id);
            }
        }

        $items = CatalogItem::whereIn('catalog_id',$array)
                    ->count();

        return view("catalog.edit", compact("record", "form","active","items"));
    }

    public function actionEditPost($id, CatalogEditPostRequest $request): array
    {
        $record = Catalog::query()->findOrFail($id);
        Gate::authorize("catalog-edit", $record);
        $form = new CatalogAdminForm($record);
        $form->formSave(request()->all());

        $record = Catalog::query()
            ->with('recursiveChildren')
            ->findOrFail($id);

        $array = array();

        array_push($array,intval($id));

        foreach ($record->recursiveChildren as $category){
            array_push($array,$category->id);
            foreach ($category->recursiveChildren as $last){
                array_push($array,$last->id);
            }
        }

        CatalogCatalogCharacteristic::whereIn('catalog_id',$array)
                                    ->delete();

        foreach ($array as $category){
            if (request()->characteristics != null){
                foreach (request()->characteristics as $item){
                    CatalogCatalogCharacteristic::firstOrCreate([
                        'catalog_id' => $category,
                        'catalog_characteristic_id' => $item,
                    ]);
                }
            }
        }

        if (isset($request->is_active)){
            Catalog::query()->whereIn('id',$array)
                ->update([
                    'is_active' => $request->is_active == 'on' ? 1 : 0
                ]);
        }



        return ["redirect" => route("catalog.list", 0)];
    }

    public function actionDel($id)
    {
        $record = Catalog::query()->findOrFail($id);
        Gate::authorize("catalog-del", $record);
        $record->delete();
        return redirect(route("catalog.list", 0));
    }
}
