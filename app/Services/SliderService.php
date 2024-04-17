<?php

namespace App\Services;

use App\Models\Slider;
use Carbon\Carbon;

class SliderService
{

    public function __construct(protected Slider $slider)
    {
    }

    public function index(){
        $items =  $this->slider->query()
            ->where('dt_start','<=',Carbon::now())
            ->where('dt_end','>=',Carbon::now())
            ->orderByDesc('dt_start')
            ->get();

        foreach ($items as $item){
            $images = json_decode($item->image);
            if ($images != null)
            $item->main_img = $images[0]->img;
        }

        return $items;

    }
}
