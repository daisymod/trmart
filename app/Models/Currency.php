<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['symbol','name','rate_start','rate_end','rate_cart'];

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class);
    }

    public function delete()
    {
        foreach ($this->rates as $rate) {
            $rate->delete();
        }
        parent::delete();
    }
}
