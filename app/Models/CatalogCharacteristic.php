<?php

namespace App\Models;

use App\Traits\LanguageTrait;
use App\Traits\ModelDNDTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogCharacteristic extends Model
{
    use HasFactory, ModelDNDTrait, LanguageTrait;

    public function items()
    {
        return $this->hasMany(CatalogCharacteristicItem::class);
    }


    public function catalog()
    {
        return $this->hasMany(CatalogCatalogCharacteristic::class);
    }
}
