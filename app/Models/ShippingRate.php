<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
