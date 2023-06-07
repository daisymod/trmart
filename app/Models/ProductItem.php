<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_id','color','size','price','count','sale'];


    public function sizeData()
    {
        return $this->belongsTo(CatalogCharacteristicItem::class, "size");
    }

    public function colorData()
    {
        return $this->belongsTo(CatalogCharacteristicItem::class, "color");
    }

    public function catalogItem()
    {
        return $this->belongsTo(CatalogItem::class, "item_id");
    }

}
