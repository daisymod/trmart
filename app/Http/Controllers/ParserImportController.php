<?php

namespace App\Http\Controllers;

use App\Forms\ColorEditForm;
use App\Models\Color;
use App\Models\Company;
use App\Services\ParseImportService;
use App\Services\ParseStatisticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ParserImportController extends Controller
{
    public function __construct(protected ParseImportService $service)
    {
    }

    public function index(Request $request){
        Gate::authorize("brand-list");
        $records = $this->service->getAll($request);
        return view("parseImport.list", compact("records"));
    }

}
