<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name_ru','name_en','country_id'];


    public function countries()
    {
        return $this->belongsTo(Country::class);
    }
}
