<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "payment_id",
        "quota",
        "quota_completion_date",
    ];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function payment(){
        return $this->hasOne('App\Models\Payment', 'id', 'payment_id');
    }

}
