<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChat extends Model
{
    use HasFactory;

    protected $fillable= [
        "chat_group_id",
        "message",
        "answer",
    ];

    public function user(){
        return $this->hasOne('App\Models\ChatGroup', 'id', 'chat_group_id');
    }

}
