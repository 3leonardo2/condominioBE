<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        // 1. Validar datos según tu modelo relacional
        $request->validate([
            'nombre' => 'required|string',
            'apellido_p' => 'required|string',
            'pass' => 'required|min:6',
            'email' => 'required|email|unique:usuarios,email' // Asumiendo campo email
        ]);

        // 2. Crear Persona primero
        $persona = Persona::create([
            'nombre' => $request->nombre,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m,
            'celular' => $request->celular,
            'activo' => true
        ]);

        // 3. Crear Usuario vinculado
        $usuario = Usuario::create([
            'id_persona' => $persona->id,
            'pass' => Hash::make($request->pass),
            'admin' => false
        ]);

        return response()->json(['message' => 'Registro exitoso'], 201);
    }
}