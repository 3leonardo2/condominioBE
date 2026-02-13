<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
    'id',
    'user_id',
    'username',
    'message',
    'created_at',
    'updated_at'
    ];
}
