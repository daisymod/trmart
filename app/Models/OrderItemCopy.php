<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemCopy extends Model
{
    use HasFactory;

    public function item()
    {
        return $this->belongsTo(CatalogItem::class, "catalog_item_id");
    }
}
