<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UpdatePassword extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereIn('id',[1,23,29,3])
            ->update(
                [
                    'password' => Hash::make('$2y$10$tgc2xlSMqy0gC'),
                ]
            );
    }
}
