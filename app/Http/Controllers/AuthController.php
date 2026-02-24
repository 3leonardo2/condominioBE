<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pass' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->pass, $usuario->pass)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Bloquear si no ha verificado email
        if (!$usuario->email_verified_at) {
            return response()->json(['message' => 'Debes verificar tu correo antes de entrar.'], 403);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'access_token' => $token,
            'user' => $usuario
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido_p' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'pass' => 'required|min:6',
        ]);

        try {
            $persona = Persona::create([
                'nombre' => $request->nombre,
                'apellido_p' => $request->apellido_p,
                'celular' => $request->celular ?? null,
                'activo' => true
            ]);

            $usuario = Usuario::create([
                'id_persona' => $persona->id,
                'email' => $request->email,
                'pass' => Hash::make($request->pass),
                'admin' => false
            ]);

            // Dispara el evento que envía el correo automático de Laravel
            event(new Registered($usuario));

            return response()->json([
                'message' => 'Registro exitoso. Por favor revisa tu correo para verificar tu cuenta.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método para procesar el clic en el correo
    public function verify(Request $request) {
        $usuario = Usuario::findOrFail($request->id);

        if (!hash_equals((string) $request->hash, sha1($usuario->getEmailForVerification()))) {
            return response()->json(['message' => 'Enlace inválido'], 403);
        }

        if ($usuario->hasVerifiedEmail()) {
            return response()->json(['message' => 'Ya verificado']);
        }

        $usuario->markEmailAsVerified();
        return response()->json(['message' => 'Email verificado con éxito']);
    }
}