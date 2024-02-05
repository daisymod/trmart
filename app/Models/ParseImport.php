<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParseImport extends Model
{
    use HasFactory;

    protected $fillable = ['job_id','domain','time','file','totalCount','status','error','uuid','url','catalog','merchant'];

    public function user()
    {
        return $this->belongsTo(User::class, "merchant",'id');
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::class, "catalog",'id');
    }

}
