<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Slider extends Model
{
    use HasFactory;

    protected $table = "sliders";

    public function catalog()
    {
        return $this->belongsToMany(Catalog::class);
    }
}
