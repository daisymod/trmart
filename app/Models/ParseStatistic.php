<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParseStatistic extends Model
{
    use HasFactory;

    protected $fillable = ['job_id','user_id','start_parse','end_parse','file','count_of_lines','status','load_lines'];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function job()
    {
        return $this->belongsTo(Job::class, "job_id");
    }
}
