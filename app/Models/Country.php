<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name_ru','name_en','vk_id'];

    public function city()
    {
        return $this->hasMany('App\Models\City', 'country_id');
    }
}
