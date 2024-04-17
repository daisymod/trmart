<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','count','price','items','is_checked'];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
