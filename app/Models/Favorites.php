<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table = "favorites";
    protected $fillable = ["user_id", "catalog_items_id"];
}
