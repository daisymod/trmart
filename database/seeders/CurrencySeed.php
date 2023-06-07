<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->truncate();
        DB::table('currency_rates')->truncate();

        $currency = [
            [
                'id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'name' => 'Тенге (KZT)',
                'rate_start' => 1,
                'rate_end' => 1,
                'rate_cart' => 1,
                'symbol' => '₸'
            ],
            [
                'id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'name' => 'Лира (TRY)',
                'rate_start' => 1,
                'rate_end' => 1,
                'rate_cart' => 1,
                'symbol' => '₺l'
            ],
            [
                'id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'name' => 'Рубль (RUS)',
                'rate_start' => 1,
                'rate_end' => 1,
                'rate_cart' => 1,
                'symbol' => '₽'
            ],
            [
                'id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'name' => 'Гривна (UA)',
                'rate_start' => 1,
                'rate_end' => 1,
                'rate_cart' => 1,
                'symbol' => '₴'
            ],
        ];

        foreach ($currency as $items){
            Currency::query()->create($items);
        }

        $rates = [
            [
                'id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 1,
                'currency_to_id' => 2,
                'rate_start' => 1,
                'rate_end' => '0.04',
            ],
            [
                'id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 2,
                'currency_to_id' => 1,
                'rate_start' => 1,
                'rate_end' => '24.49',
            ],
            [
                'id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 1,
                'currency_to_id' => 3,
                'rate_start' => 1,
                'rate_end' => '0.15',
            ],
            [
                'id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 3,
                'currency_to_id' => 1,
                'rate_start' => 1,
                'rate_end' => '6.59',
            ],
            [
                'id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 1,
                'currency_to_id' => 4,
                'rate_start' => 1,
                'rate_end' => '0.08',
            ],
            [
                'id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 4,
                'currency_to_id' => 1,
                'rate_start' => 1,
                'rate_end' => '12.47',
            ],
            [
                'id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 2,
                'currency_to_id' => 3,
                'rate_start' => 1,
                'rate_end' => '3.72',
            ],
            [
                'id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 3,
                'currency_to_id' => 2,
                'rate_start' => 1,
                'rate_end' => '0.27',
            ],
            [
                'id' => 9,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 2,
                'currency_to_id' => 4,
                'rate_start' => 1,
                'rate_end' => '1.96',
            ],
            [
                'id' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 4,
                'currency_to_id' => 2,
                'rate_start' => 1,
                'rate_end' => '0.51',
            ],
            [
                'id' => 11,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 3,
                'currency_to_id' => 4,
                'rate_start' => 1,
                'rate_end' => '0.53',
            ],
            [
                'id' => 12,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'currency_id' => 4,
                'currency_to_id' => 3,
                'rate_start' => 1,
                'rate_end' => '1.89',
            ],
        ];

        foreach ($rates as $items){
            CurrencyRate::query()->create($items);
        }
    }
}
