<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;


    public function item()
    {
        return $this->belongsTo(CatalogItem::class, "catalog_item_id");
    }

    protected $fillable = ['order_id','user_id','user_name','catalog_item_id','image','name','article','size','count','price','color','price_tenge'];

}
