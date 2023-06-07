<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceTengeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = OrderItem::all();

        foreach ($items as $item){
            $item->price_tenge = intval(ceil(24.49 * $item->price));
            $item->save();
        }
    }
}
