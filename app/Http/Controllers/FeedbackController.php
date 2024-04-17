<?php

namespace App\Http\Controllers;

use App\Services\FeedbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FeedbackController extends Controller
{
    public function __construct(public FeedbackService $service)
    {
    }


    public function create(Request $request,$id){
        $this->service->create($request->all(),$id);

        return Redirect::route('shop.item',['id' => $id]);
    }
}
