<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MakeMerchantUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
                [
                    'shot_name' => 'test',
                    'name' => 'test',
                    'phone' => '+0 (000) 000-0000',
                    'email' => 'dmitriii.kukharchuk@gmail.com',
                    'password' => Hash::make('almaty2023'),
                    'active' => 'Y',
                    'role' => 'merchant',
                ]
            );
    }
}
