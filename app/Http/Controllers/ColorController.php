<?php

namespace App\Http\Controllers;

use App\Forms\ColorEditForm;
use App\Models\Color;
use App\Services\ColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ColorController extends Controller
{
    public function __construct(protected ColorService $service)
    {
    }

    public function index(){
        Gate::authorize("brand-list");
        $records = $this->service->getAll();

        return view("color.list", compact("records"));
    }

    public function create(){
        Gate::authorize("brand-edit");
        $form = new ColorEditForm(new Color());
        $form = $form->formRenderAdd();
        return view("color.edit", compact("form"));
    }

    public function show($id){
        Gate::authorize("brand-edit");
        $record = $this->service->show($id);
        $form = new ColorEditForm($record);
        $form = $form->formRenderEdit();
        return view("color.edit", compact("record", "form"));
    }

    public function store(Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $this->service->create($attributes);
        return ["redirect" => route("color.list", request("parent", [0])[0])];
    }

    public function update($id, Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $this->service->update($attributes,$id);
        return ["redirect" => route("color.list", request("parent", [0])[0])];
    }

    public function delete($id){
        Gate::authorize("brand-update");
        $this->service->delete($id);
        return ["redirect" => route("color.list", request("parent", [0])[0])];
    }
}
