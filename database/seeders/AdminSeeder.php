<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\Usuario;

class AdminSeeder extends Seeder {
    public function run(): void {
        $persona = Persona::create([
            'nombre'     => 'Admin',
            'apellido_p' => 'Principal',
            'activo'     => true,
        ]);

        Usuario::create([
            'id_persona'        => $persona->id,
            'email'             => 'admin@happycommunity.com',
            'pass'              => Hash::make('admin123'),
            'admin'             => true,
            'email_verified_at' => now(), // ya verificado
        ]);
    }
}