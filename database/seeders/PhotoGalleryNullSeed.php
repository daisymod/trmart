<?php

namespace Database\Seeders;

use App\Models\ProductItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhotoGalleryNullSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = ProductItem::where('image','=','[]')
            ->get();

        foreach ($items as $item){
            $checkProductItemColor = ProductItem::where('color','=',$item->color)
                ->where('item_id','=',$item->item_id)
                ->where('image','!=','[]')
                ->first();
            if (!empty($checkProductItemColor->image)){
                $item->image = $checkProductItemColor->image;
                $item->save();
            }
        }
    }
}
