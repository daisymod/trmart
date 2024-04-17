<?php

namespace App\Models;

use App\Traits\LanguageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogItem extends Model
{
    use HasFactory, LanguageTrait,SoftDeletes;


    /**
     * Атрибуты, которые должны быть преобразованы в дату
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = ["barcode", "user_id","name_ru",'name_kz','name_tr','brand','article',
        'country_title','city_id','country_id','equipment_ru','equipment_tr',
        'equipment_kz','body_ru','body_tr','body_kz','active','status','status_text','sex',
        'weight','style','size','sale','length','catalog_id','price','image','stock','reason','gpt_translate',
        'length','width','height'];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class, "catalog_id");
    }

    public function colors()
    {
        return $this->belongsTo(CatalogCharacteristicItem::class, "color");
    }

    public function sizes()
    {
        return $this->belongsTo(CatalogCharacteristicItem::class, "size");
    }

    public function lengths()
    {
        return $this->belongsTo(CatalogCharacteristicItem::class, "length");
    }


    public function compound()
    {
        return $this->hasMany(ItemCompoundTable::class, "item_id");
    }


    public function productsData()
    {
        return $this->belongsTo(ProductItem::class, 'id',"item_id");
    }


    public function weights()
    {
        return $this->belongsTo(CatalogCharacteristicItem::class, "weight");
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function order()
    {
        return $this->belongsTo(OrderItem::class, "catalog_item_id");
    }


    public function dynamic()
    {
        return $this->belongsTo(CatalogItemDynamicCharacteristic::class, 'id',"item_id");
    }


    public function image()
    {
        $image = json_decode($this->image, true);
        if (!empty($image)) {
            return $image[0]["img"];
        } else {
            return "/i/no_image.png";
        }
    }

    public function images()
    {
        return json_decode($this->image, true);
    }
}
