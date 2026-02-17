<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model {
    protected $table = 'personas'; // Nombre exacto en tu BD
    protected $fillable = ['nombre', 'apellido_p', 'apellido_m', 'celular', 'activo'];
    public $timestamps = false; // Solo si no tienes campos 'created_at' y 'updated_at'
}