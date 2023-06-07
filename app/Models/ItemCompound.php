<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCompound extends Model
{
    use HasFactory;

    protected $fillable = ['item_id','percent','name_ru','name_kz','name_tr'];

}
