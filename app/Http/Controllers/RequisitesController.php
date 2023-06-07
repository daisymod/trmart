<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequisitesController extends Controller
{
    public function index(){
        return view("requisites");
    }
}
