<?php

namespace App\Http\Controllers;

use App\Jobs\TestLoadChainJob;
use App\Mail\ParserMail;
use App\Models\User;
use App\Services\CatalogItemsExcelLoadServiceTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index(Request $request){
        $adminUser = User::where('id','=',1)
            ->first();
        Mail::to($adminUser->email)->send(new ParserMail($adminUser,[],$user->lang ?? 'tr','url'));
    }
}
