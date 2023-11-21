<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = ['name_ru','parent_id','commission','name_kz','name_tr','is_active'];

    public function parent()
    {
        return $this->belongsTo(self::class, "parent_id", "id");
    }

    public function child()
    {
        return $this->hasMany(self::class, "parent_id", "id");
    }

    public function recursiveChildren() {
        return $this->child()->with('recursiveChildren');
    }

    public function delete()
    {
        foreach ($this->child as $child) {
            $child->delete();
        }
        parent::delete();
    }

    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(CatalogCharacteristic::class);
    }

    public function items()
    {
        return $this->hasMany(CatalogItem::class, "catalog_id", "id");
    }
}
