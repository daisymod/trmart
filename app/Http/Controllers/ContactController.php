<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function index(){
        return view("contact-us");
    }

    public function sendMessage(Request $request){
        Mail::to('admin@turkiyemart.com')->send(new ContactUsMail($request->all()));

        return ["redirect" => route("contactus.index", request("parent", [0])[0])];
    }

}
