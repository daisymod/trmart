<?php

namespace App\Forms;

use App\Fields\BasicField;
use App\Fields\TextboxField;
use App\Models\Merchant;
use App\Models\User;
use App\Traits\FormModelTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MerchantRegForm extends BasicForm
{

    public static string $formNamePref = "merchant.form.";
    public static array $formFields = [
        "company_name" => [TextboxField::class],
        "phone" => [TextboxField::class, "type"=>"tel"],
        "email" => [TextboxField::class, "type"=>"email"],
        "shot_name" => [TextboxField::class],
        //"full_name" => [TextboxField::class],
        //"address" => [TextboxField::class],
        "password" => [TextboxField::class, "type"=>"password"],
        "password_confirmation" => [TextboxField::class, "type"=>"password"],
    ];

    public static function reg($data)
    {
        $user = new Merchant($data);
        $user->password = Hash::make($data["password"]);
        $user->role = "merchant";
        $user->step = 1;
        $user->name = $data["company_name"];
        $user->save();
        Auth::loginUsingId($user->id);
        return $user;
    }

}
