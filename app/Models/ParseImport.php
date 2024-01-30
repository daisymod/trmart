<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParseImport extends Model
{
    use HasFactory;

    protected $fillable = ['job_id','domain','time','file','totalCount','status','error','uuid','url'];

}
