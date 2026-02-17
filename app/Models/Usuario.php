<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable {
    protected $table = 'usuarios';
    protected $fillable = ['id_persona', 'email', 'pass', 'admin'];
    protected $hidden = ['pass'];
    public $timestamps = false; // Tu esquema no muestra timestamps

    public function getAuthPassword() {
    return $this->pass;
}
}

