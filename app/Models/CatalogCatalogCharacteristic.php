<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogCatalogCharacteristic extends Model
{
    use HasFactory;

    protected $table = 'catalog_catalog_characteristic';

    protected $fillable = ['catalog_characteristic_id','catalog_id'];


    public $timestamps = false;

    public function items()
    {
        return $this->belongsTo(CatalogCharacteristic::class,'catalog_characteristic_id');
    }
}
