<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerDep extends Model
{
    protected $table = 'per_dep';
    protected $fillable = ['id_persona', 'id_depa', 'id_rol', 'residente', 'codigo'];
    public $timestamps = false;
    public $incrementing = false;   // ← no hay id autoincremental
    protected $primaryKey = null;
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_depa');
    }
}
