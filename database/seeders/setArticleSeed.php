<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class setArticleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $items = CatalogItem::all();

       $index = 1;
       foreach ($items as $item){
           $item->article = substr("0000000000".$index, strlen($index + 1));
           $item->save();

           $index++;
       }

    }
}
