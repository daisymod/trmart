<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSettings extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "name","catalog",'brand','article','barcode','price_from',
        'price_to','user','status','sort_by','limit','page','active'];
}
