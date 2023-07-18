<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','company_name','shop_name','bin','phone',
        'email','legal_address','fio','iin','ogrn','first_name','last_name','patronymic',
        'legal_address_city','legal_address_street','legal_address_number','legal_address_office',
        'city','tax_office','street','number','office'];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

}
