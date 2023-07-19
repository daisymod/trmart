<?php

namespace App\Http\Controllers;

use App\Jobs\TestLoadChainJob;
use App\Models\User;
use App\Services\CatalogItemsExcelLoadServiceTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index(Request $request){
          
            TestLoadChainJob::dispatch(CatalogItemsExcelLoadServiceTest::getArrayFromFile($request->file("file")),User::where('id','=',29)->first());
    }
}
