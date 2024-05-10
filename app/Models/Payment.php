<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "productId",
        "receiptToken",
        "purchase_date",
        "status",
    ];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function package(){
        return $this->hasOne('App\Models\Package', 'id', 'productId');
    }

}
