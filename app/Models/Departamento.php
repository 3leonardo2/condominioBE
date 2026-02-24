<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model {
    protected $table = 'departamentos';
    protected $fillable = ['depa', 'moroso', 'codigo'];
    public $timestamps = false;

    public function residentes() {
        return $this->hasMany(PerDep::class, 'id_depa');
    }
}