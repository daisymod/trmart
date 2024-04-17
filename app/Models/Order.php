<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','status','postcode','article','merchant','merchant_id','postcode_id','barcode',
        'pdf','res','real_weight','surname','name','middle_name','phone','email','country_name',
        'region_id','region_name','area_id','area_name','country_id','city_name','city_id',
        'street','house_number','room','time','comment','price','pickup','delivery_price',
        'tr_delivery_price','delivery_dt_start','delivery_dt_end','payment','delivery_kz_weighing',
        'delivery_tr_weighing','sale','payment_id'
    ];


    public function items()
    {
        return $this->hasMany(OrderItem::class, "order_id", "id");
    }


    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function merchantData()
    {
        return $this->belongsTo(User::class, "merchant_id", "id");
    }

    public function commission()
    {
        return $this->hasMany(OrderCommission::class, "order_id");
    }
}
