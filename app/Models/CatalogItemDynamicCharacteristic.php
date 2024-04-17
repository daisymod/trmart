<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogItemDynamicCharacteristic extends Model
{
    use HasFactory;

    protected $fillable = ['item_id','characteristic_id','name_ru','name_kz','name_tr'];

    public function category()
    {
        return $this->belongsTo(CatalogCharacteristic::class, "characteristic_id");
    }

}
