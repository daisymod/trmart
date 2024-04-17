<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Compound;
class ItemCompoundTable extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['item_id','percent','compound_id'];

    public function compound(){
        return $this->belongsTo(Compound::class,'compound_id','id');
    }
}
