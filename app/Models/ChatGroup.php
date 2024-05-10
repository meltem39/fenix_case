<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "user_package_id",
        "title",
    ];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function userPackage(){
        return $this->hasOne('App\Models\UserPackage', 'id', 'user_package_id');
    }
}
