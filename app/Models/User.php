<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "phone",
        //"password",
        //"last_seen"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getOnPhone($phone)
    {
        $phoneLogin =  preg_replace("/[^0-9]/", "", request("phone"));

        //$usaPhone = preg_match('/\b\[(.]?d{3}[).]?\d{3}[-.]?\d{4}\b/', $phoneLogin);

        $phoneUsa = sprintf("+%s (%s) %s-%s",
            substr($phoneLogin, 0, 1),
            substr($phoneLogin, 1, 3),
            substr($phoneLogin, 4, 3),
            substr($phoneLogin, 7)
        );


        $user =  User::query()
            ->where("phone", $phoneLogin)
            ->orWhere("phone", $phone)
            ->orWhere("phone", $phoneUsa)
            ->first();

        return $user;
    }


    public function company()
    {
        return $this->belongsTo(Company::class,'id','user_id');
    }
}
