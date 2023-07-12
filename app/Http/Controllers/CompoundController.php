<?php

namespace App\Http\Controllers;
use App\Forms\CompoundEditForm;
use App\Services\CompoundModelService;
use App\Models\Compound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;



class CompoundController extends Controller
{
    public function __construct(protected CompoundModelService $service)
    {
    }

    public function index(){
        Gate::authorize("brand-list");
        $records = $this->service->getAll();

        return view("compound.list", compact("records"));
    }

    public function create(){
        Gate::authorize("brand-edit");
        $form = new CompoundEditForm(new Compound);
        $form = $form->formRenderAdd();
        return view("compound.edit", compact("form"));
    }

    public function show($id){
        Gate::authorize("brand-edit");
        $record = $this->service->show($id);

        $form = new CompoundEditForm($record);
        $form = $form->formRenderEdit();
        return view("compound.edit", compact("record", "form"));
    }

    public function store(Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $this->service->create($attributes);
        return ["redirect" => route("compound.list", request("parent", [0])[0])];
    }

    public function update($id, Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $this->service->update($attributes,$id);
        return ["redirect" => route("compound.list", request("parent", [0])[0])];
    }

    public function delete($id){
        Gate::authorize("brand-update");
        $this->service->delete($id);
        return redirect('/compound/list');
    }

}
