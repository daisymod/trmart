<?php

namespace Database\Seeders;

use App\Models\CatalogItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateArticul extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $items = CatalogItem::all();

        foreach ($items as $item){
            $item->article = substr("0000000000".$item->id, strlen($item->id));
            $item->save();
        }

    }
}
