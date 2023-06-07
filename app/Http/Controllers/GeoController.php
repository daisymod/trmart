<?php

namespace App\Http\Controllers;
//App
use App\Imports\KPLocationImport;
use App\Imports\TRLocationImport;
use App\Services\CountryService;
use App\Forms\CountryEditForm;
use App\Services\CityService;
use App\Forms\CityEditForm;
use App\Models\KPPostCode;
use App\Models\KPLocation;
use App\Models\Country;
use App\Models\City;
//Illuminate
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
//Maatwebsite
use Maatwebsite\Excel\Facades\Excel;

class GeoController extends Controller
{

    public function __construct(public CountryService $country, public CityService $city)
    {

    }

    public function apiIndex(){
        $records = $this->country->getAll();

        return response()->json(
            [
                'items' => $records
            ],
            201
        );
    }

    public function apiRegionIndex($id){
        if ($id === '3') {
            $records = KPLocation::query()
                ->orderBy('name')
                ->where('parent_id', 0)
                ->select('id', 'name')
                ->get();
            return response()->json(['items' => $records],201);
        } else {
            $records = $this->city->getById($id);
            return response()->json(['items' => $records],201);
        }
    }

    public function apiAreaIndex($id){
        $records = KPLocation::query()
            ->orderBy('name')
            ->where('parent_id', $id)
            ->select('id', 'name')
            ->get()
            ->values();
        return response()->json(['items' => $records],201);
    }

    public function apiCityIndex($id){
        $records = KPLocation::query()
            ->orderBy('name')
            ->where('parent_id', $id)
            ->select('id', 'name')
            ->get()
            ->values();
        return response()->json(['items' => $records],201);
    }

    public function getPostcode($id){
        $postCodes = KPPostCode::query()
            ->orderBy('title')
            ->where('kp_locations_id', $id)
            ->select('id', 'title')
            ->get()
            ->values();

        return response()->json(['items' => $postCodes], 201);
    }

    public function index(){
        Gate::authorize("country-list");
        $records = $this->country->getAll();

        return view("geo.list", compact("records"));
    }

    public function create(){
        Gate::authorize("country-edit");
        $form = new CountryEditForm(new Country());
        $form = $form->formRenderAdd();
        return view("geo.edit", compact("form"));
    }

    public function show($id){
        Gate::authorize("country-edit");
        $record = $this->country->show($id);

        $form = new CountryEditForm($record);
        $form = $form->formRenderEdit();
        return view("geo.edit", compact("record", "form"));
    }

    public function store(Request $request){
        Gate::authorize("country-update");
        $attributes = $request->all();
        $this->country->create($attributes);
        return ["redirect" => route("geo.list", request("parent", [0])[0])];
    }

    public function update($id, Request $request){
        Gate::authorize("country-update");
        $attributes = $request->all();
        $this->country->update($attributes,$id);
        return ["redirect" => route("geo.list", request("parent", [0])[0])];
    }

    public function cityIndex(Request $request){
        Gate::authorize("city-list");
        $records = $this->city->getAll($request->all());

        return view("geo.list", compact("records"));
    }

    public function cityShow($id){
        Gate::authorize("city-edit");
        $record = $this->city->show($id);

        $form = new CityEditForm($record);
        $form = $form->formRenderEdit();
        return view("geo.city-edit", compact("record", "form"));
    }

    public function cityUpdate($id, Request $request){
        Gate::authorize("city-update");
        $attributes = $request->all();
        $this->city->update($attributes,$id);
        return ["redirect" => route("geo.list", request("parent", [0])[0])];
    }

    public function cityDelete($id, Request $request){
        Gate::authorize("city-delete");
        $this->country->delete($id);
        return \Redirect::route('geo.city-list');
    }

    public function cityCreate(){
        Gate::authorize("country-edit");
        $form = new CityEditForm(new City());
        $form = $form->formRenderAdd();
        return view("geo.city-create", compact("form"));
    }

    public function cityStore(Request $request,$id){
        Gate::authorize("country-update");
        $attributes = $request->all();
        $this->city->create($attributes,$id);
        return ["redirect" => route("geo.list", request("parent", [0])[0])];
    }

    public function actionExcelLoad()
    {
        Gate::authorize("country-update-excel-load");
        if (request()->hasFile("file") and request()->file("file")->getMimeType() == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            Excel::import(new KPLocationImport, request()->file("file"));
        }
        return redirect(route("geo.list"));
    }

    public function actionTrExcelLoad()
    {
        Gate::authorize("country-update-excel-load");
        if (request()->hasFile("file") and request()->file("file")->getMimeType() == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            Excel::import(new TRLocationImport(), request()->file("file"));
        }
        return redirect(route("geo.list"));
    }
}
