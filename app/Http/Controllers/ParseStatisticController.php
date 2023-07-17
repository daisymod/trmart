<?php

namespace App\Http\Controllers;

use App\Forms\ColorEditForm;
use App\Models\Color;
use App\Services\ParseStatisticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ParseStatisticController extends Controller
{
    public function __construct(protected ParseStatisticService $service)
    {
    }

    public function index(){
        Gate::authorize("brand-list");
        $records = $this->service->getAll();
        return view("color.list", compact("records"));
    }

    public function show($id){
        Gate::authorize("brand-edit");
        $record = $this->service->show($id);
        $form = new ColorEditForm($record);
        $form = $form->formRenderEdit();
        return view("color.edit", compact("record", "form"));
    }

}
