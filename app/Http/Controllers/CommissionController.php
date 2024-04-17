<?php

namespace App\Http\Controllers;

use App\Forms\CommissionEditForm;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class CommissionController extends Controller
{

    public function index(){
        Gate::authorize("commission-list");
        $parent_id = Catalog::query()
            ->orderBy("name_ru", "ASC")
            ->where('parent_id','=',0)
            ->get()->pluck('id');


        $records = Catalog::query()
            ->orderBy("name_ru", "ASC")
            ->where('parent_id','=',0)
            ->orWhereIn('parent_id',$parent_id)
            ->paginate(50);

        return view("commission.list", compact("records"));
    }

    public function show($id){
        Gate::authorize("commission-edit");
        $record = Catalog::query()
            ->findOrFail($id);

        $form = new CommissionEditForm($record);
        $form = $form->formRenderEdit();
        return view("commission.edit", compact("record", "form"));
    }


    public function update(Catalog $id, Request $request){
        Gate::authorize("commission-update");
        $attributes = $request->all();
        Catalog::query()->where('id','=',$id->id)
            ->update($attributes);


        $parent_id = Catalog::query()
            ->orderBy("name_ru", "ASC")
            ->where('parent_id','=',$id->id)
            ->get()->pluck('id');

        Catalog::query()->where('id','=',$id->id)
            ->orWhereIn('id',$parent_id)
            ->orWhereIn('parent_id',$parent_id)
            ->update([
                'commission' => $request->commission,
            ]);

        return ["redirect" => route("commission.list")];
    }



}
