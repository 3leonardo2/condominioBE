<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Usuario extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'usuarios';
    protected $fillable = ['id_persona', 'email', 'pass', 'admin', 'email_verified_at', 'invitation_token', 'invitation_expires_at'];
    protected $hidden = ['pass', 'invitation_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'invitation_expires_at' => 'datetime',
        'admin' => 'boolean',
    ];
    public $timestamps = false;

    public function getAuthPassword()
    {
        return $this->pass;
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }
}
