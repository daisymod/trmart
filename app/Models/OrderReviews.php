<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReviews extends Model
{

    protected $fillable = ["user_id", "order_id", "rating", "subject"];
}
