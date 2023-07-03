<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\ProductItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhotoGallerySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = ProductItem::where('image','=',null)
            ->get();

        foreach ($items as $item){
            $item->image = '[{"file":"\/img\/no_img.jpeg","name":"\/img\/no_img.jpeg","img":"\/img\/no_img.jpeg","small":"\/img\/no_img.jpeg"}]';
            $item->save();
        }
    }
}
