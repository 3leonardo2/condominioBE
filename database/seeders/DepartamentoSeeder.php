<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Rol;

class DepartamentoSeeder extends Seeder {
    public function run(): void {
        // Departamentos de prueba
        Departamento::insert([
            ['depa' => 'A-101', 'moroso' => false],
            ['depa' => 'A-102', 'moroso' => false],
            ['depa' => 'B-101', 'moroso' => false],
            ['depa' => 'B-102', 'moroso' => true],
        ]);

        // Roles básicos
        Rol::insert([
            ['rol' => 'Propietario'],
            ['rol' => 'Inquilino'],
        ]);
    }
}