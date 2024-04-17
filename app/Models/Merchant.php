<?php

namespace App\Models;

use App\Fields\TextboxField;
use App\Traits\FormModelTrait;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = "users";
    protected $fillable = ["name", "phone", "email", "shot_name", "password"];

    public function sale_category()
    {
        return $this->belongsToMany(Catalog::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'id','user_id');
    }
}
