<?php

namespace App\Http\Controllers;

use App\Forms\BrandEditForm;
use App\Models\Brand;
use App\Services\AutoService;
use App\Services\BrandService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AutoControllerDelivery extends Controller
{

    public function __construct(protected AutoService $service)
    {
    }

    public function index(){
        Gate::authorize("brand-list");
        $records = $this->service->getAll();

        return view("auto.list", compact("records"));
    }

    public function create(){
        Gate::authorize("brand-edit");
        return view("auto.create");
    }

    public function show($id){
        Gate::authorize("brand-edit");
        $record = $this->service->show($id);
        return view("auto.edit", compact("record"));
    }

    public function store(Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $this->service->create($attributes);
        return ["redirect" => route("auto.index")];
    }

    public function update($id, Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $this->service->update($attributes,$id);
        return ["redirect" => route("auto.index")];
    }

    public function delete($id){
        $this->service->delete($id);
        return redirect(route("auto.index"));
    }

}
