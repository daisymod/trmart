<?php

namespace App\Http\Controllers;

use App\Events\CheckNotExistCharacteristicEvent;
use App\Mail\NewOrderMail;
use App\Models\News;
use App\Models\User;
use App\Services\BrandService;
use App\Services\CatalogItemActionService;
use App\Services\SendPulseService;
use App\Services\SliderService;
use http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{

    public function __construct(protected CatalogItemActionService $service,protected SliderService $slider,protected BrandService $brand)
    {
    }


    public function setLang(){
        if (Auth::check()) {
            User::where('id','=',Auth::user()->id)
                ->update(
                    [
                        'lang' => app()->getLocale()
                    ]
                );
        }

        return response()->json();
    }

    public function test(){
        $user = User::where('id','=',65)
            ->first();
        Mail::to($user->email)->send(new NewOrderMail($user,$user->role));
    }

    public function actionIndex()
    {
        $newItems = $this->service->getNewItems();
        $slider = $this->slider->index();
        $top = $this->service->topItems();
        $brands = $this->brand->getAll();

        return view("index",['newItems' => $newItems,'slider' => $slider,'top' => $top,'brands' => $brands]);
    }
}
