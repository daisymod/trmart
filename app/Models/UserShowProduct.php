<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShowProduct extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','product_id','session'];


    public function product()
    {
        return $this->belongsTo(CatalogItem::class, "product_id");
    }
}
