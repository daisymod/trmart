<?php

namespace App\Http\Controllers;

use App\Forms\BrandEditForm;
use App\Models\Brand;
use App\Services\BrandService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BrandController extends Controller
{

    public function __construct(protected BrandService $service)
    {
    }

    public function index(){
        Gate::authorize("brand-list");
        $records = $this->service->getAll();

        return view("brand.list", compact("records"));
    }

    public function create(){
        Gate::authorize("brand-edit");
        $form = new BrandEditForm(new Brand());
        $form = $form->formRenderAdd();
        return view("brand.edit", compact("form"));
    }

    public function show($id){
        Gate::authorize("brand-edit");
        $record = $this->service->show($id);

        $form = new BrandEditForm($record);
        $form = $form->formRenderEdit();
        return view("brand.edit", compact("record", "form"));
    }

    public function store(Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $attributes['image']  = implode(' ',$attributes['image']);
        $this->service->create($attributes);
        return ["redirect" => route("brand.list", request("parent", [0])[0])];
    }

    public function update($id, Request $request){
        Gate::authorize("brand-update");
        $attributes = $request->all();
        $attributes['image']  = implode(' ',$attributes['image']);
        $this->service->update($attributes,$id);
        return ["redirect" => route("brand.list", request("parent", [0])[0])];
    }

    public function delete($id){
        //Gate::authorize("brand-update");
        $this->service->delete($id);
        return redirect(route('brand.list'));
    }

}
