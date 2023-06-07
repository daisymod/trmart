<?php

namespace App\Services;

use App\Models\Slider;

class SliderService
{

    public function __construct(protected Slider $slider)
    {
    }

    public function index(){
        $items =  $this->slider->query()
            ->orderByDesc('id')
            ->get();

        foreach ($items as $item){
            $images = json_decode($item->image);
            $item->main_img = $images[0]->img;
        }

        return $items;

    }
}
