<?php

namespace App\Http\Controllers;

use App\Forms\AdminParserForm;
use App\Forms\MerchantParserForm;
use App\Jobs\ParserJob;
use App\Models\CatalogItem;
use App\Requests\MSRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

;


class ParserController extends Controller
{

    public function __construct(protected MSRequest $service)
    {
    }

    public function index(){
        Gate::authorize("parser-list");

        if (Auth::user()->role == 'merchant'){
            $form  = new MerchantParserForm(new CatalogItem());
        }
        else{
            $form  = new AdminParserForm(new CatalogItem());
        }

        $form = $form->formRenderEdit();
        return view("parser.index",['form' => $form]);
    }


    public function actionExcelExport(Request $request){
        ini_set('max_execution_time', 6000);

        set_time_limit(6000);
        if (!str_contains(request()->url, "www.ozdilekteyim.com")) {
            throw \Illuminate\Validation\ValidationException::withMessages(['Site Is wrong']);
        }
        ParserJob::dispatch($request->all());


        return redirect()->route('parser.list', ["send" => true]);
    }

}
