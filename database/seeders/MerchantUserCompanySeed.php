<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyEmployee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MerchantUserCompanySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('companies')->truncate();
        DB::table('company_employees')->truncate();

        $users = User::where('role','=','merchant')
            ->get();

        foreach ($users as $user){
           $company =  Company::create(
                [
                    'user_id' => $user->id,
                    'company_name' => $user->shop_name ?? 'shop_name',
                    'bin' => $user->bin,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'legal_address' => $user->address_ur,
                ]
            );

            CompanyEmployee::create(
                [
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                    'role' => 'merchant'
                ]
            );
        }


    }
}
