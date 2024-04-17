<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FormModelTrait;

class Customer extends Model
{
    protected $table = "users";
    protected $fillable = ["full_name", "name", "phone", "email", "shot_name", "country_id", "city_id"];
}
