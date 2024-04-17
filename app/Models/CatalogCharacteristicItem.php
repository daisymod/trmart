<?php

namespace App\Models;

use App\Traits\LanguageTrait;
use App\Traits\ModelDNDTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogCharacteristicItem extends Model
{
    use HasFactory, ModelDNDTrait, LanguageTrait;

    public function catalog_characteristic()
    {
        return $this->belongsTo(CatalogCharacteristic::class);
    }

    protected $fillable = ['name_ru','name_tr','name_kz','position','catalog_characteristic_id','image'];

    public function image()
    {
        $image = json_decode($this->image, true);
        if (!empty($image)) {
            return $image[0]["img"];
        } else {
            return "/i/no_image.png";
        }
    }

}
