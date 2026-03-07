<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecuperarPassword;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pass' => 'required',
            'dispositivo' => 'nullable|string'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->pass, $usuario->pass)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Bloquear si no ha verificado email
        if (!$usuario->email_verified_at) {
            return response()->json(['message' => 'Debes verificar tu correo antes de entrar.'], 403);
        }

        // Bloquear si la persona asociada está inactiva
        if (!$usuario->persona->activo) {
            return response()->json(['message' => 'Tu cuenta ha sido desactivada. Contacta al administrador.'], 403);
        }

        // Nombre del token basado en el dispositivo enviado o valor por defecto
        $nombreDispositivo = $request->dispositivo ?? 'Dispositivo desconocido';

        $token = $usuario->createToken($nombreDispositivo)->plainTextToken;

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
    public function verify(Request $request)
    {
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

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'pass_actual' => 'required',
            'pass_nueva'  => 'required|min:6|confirmed', // requiere pass_nueva_confirmation
        ]);

        $usuario = $request->user();

        if (!Hash::check($request->pass_actual, $usuario->pass)) {
            return response()->json(['message' => 'La contraseña actual es incorrecta.'], 422);
        }

        // Actualizar contraseña
        $usuario->update([
            'pass' => Hash::make($request->pass_nueva)
        ]);

        // Cerrar sesión en todos los dispositivos
        $usuario->tokens()->delete();

        return response()->json([
            'message' => 'Contraseña actualizada. Por seguridad, se cerraron todas las sesiones activas.'
        ]);
    }

    public function solicitarRecuperacion(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();
        $persona = $usuario->persona;

        // Eliminar tokens anteriores para este correo
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Generar token
        $token = Str::random(64);

        // Guardar en la tabla nativa
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $link = env('FRONTEND_URL') . '/restablecer-password?token=' . $token . '&email=' . urlencode($request->email);
        $nombre = $persona->nombre . ' ' . $persona->apellido_p;

        Mail::to($request->email)->send(new RecuperarPassword($nombre, $link));

        return response()->json([
            'message' => 'Te enviamos un correo con las instrucciones para recuperar tu contraseña.'
        ]);
    }

    // Restablecer contraseña con el token del correo
    public function restablecerPassword(Request $request)
    {
        $request->validate([
            'email'                  => 'required|email|exists:usuarios,email',
            'token'                  => 'required|string',
            'pass_nueva'             => 'required|min:6|confirmed',
        ]);

        // Buscar el token en la tabla
        $registro = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Verificar que existe y no ha expirado (60 minutos)
        if (!$registro || !Hash::check($request->token, $registro->token)) {
            return response()->json(['message' => 'El enlace es inválido.'], 422);
        }

        if (now()->diffInMinutes($registro->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json(['message' => 'El enlace ha expirado. Solicita uno nuevo.'], 422);
        }

        $usuario = Usuario::where('email', $request->email)->first();

        // Actualizar contraseña
        $usuario->update(['pass' => Hash::make($request->pass_nueva)]);

        // Cerrar sesión en todos los dispositivos
        $usuario->tokens()->delete();

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Contraseña restablecida correctamente. Por seguridad se cerraron todas las sesiones activas.'
        ]);
    }
}
