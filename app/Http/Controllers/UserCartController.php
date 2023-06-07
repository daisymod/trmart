<?php

namespace App\Http\Controllers;

use App\Services\UserCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserCartController extends Controller
{
    public function __construct(protected UserCartService $service)
    {
    }

    public function index(Request $request){

        $records = $this->service->getAll($request);

        return view("userCart.list", compact("records"));
    }


    public function show($id){

        $data = $this->service->show($id);

        $string = str_replace('}', '},', $data->items);
        $json  = str_replace('},]', '}]', $string);
        $record = json_decode( $json, true );

        for ($index = 0; $index < count($record); $index++){
            $image = json_decode($record[$index]['image']);
            array_push($record[$index],['image_url' => $image[0]->file ?? '#' ]);
        }

        return view("userCart.edit", compact("record"));
    }


    public function update($id,Request $request){

        $this->service->update($request->all(),$id);

        return redirect(route("userCart.list"))->send();
    }


}
