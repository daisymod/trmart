<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    public function rate()
    {
        return $this->hasMany(ShippingRate::class);
    }

    public function delete()
    {
        foreach ($this->rate as $rate) {
            $rate->delete();
        }
        parent::delete();
    }
}
