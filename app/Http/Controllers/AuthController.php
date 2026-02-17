<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
    // 1. Validar datos
    $request->validate([
        'nombre' => 'required|string',
        'apellido_p' => 'required|string',
        'email' => 'required|email|unique:usuarios,email',
        'pass' => 'required|min:6',
        'celular' => 'nullable|string'
    ]);

    try {
        // 2. Crear Persona primero
        $persona = Persona::create([
            'nombre' => $request->nombre,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m ?? null,
            'celular' => $request->celular ?? null,
            'activo' => true
        ]);

        // 3. Crear Usuario vinculado CON EMAIL
        $usuario = Usuario::create([
            'id_persona' => $persona->id,
            'email' => $request->email,  // ← FALTABA ESTO
            'pass' => Hash::make($request->pass),
            'admin' => false
        ]);

        return response()->json([
            'message' => 'Registro exitoso',
            'usuario' => $usuario
        ], 201);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al registrar usuario',
            'error' => $e->getMessage()
        ], 500);
    }
}
}