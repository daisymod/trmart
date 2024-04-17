<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCommission extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id','order_id','product_id','total_price','commission_price'];


}
