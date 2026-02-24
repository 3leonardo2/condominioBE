<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\PerDep;
use App\Mail\InvitacionResidente;

class InvitationController extends Controller {

    // Admin invita a un residente
    public function invite(Request $request) {
        $request->validate([
            'nombre'     => 'required|string',
            'apellido_p' => 'required|string',
            'apellido_m' => 'nullable|string',
            'celular'    => 'nullable|numeric',
            'email'      => 'required|email|unique:usuarios,email',
            'id_depa'    => 'required|exists:departamentos,id',
            'id_rol'     => 'required|exists:roles,id',
        ]);

        // Crear persona
        $persona = Persona::create([
            'nombre'     => $request->nombre,
            'apellido_p' => $request->apellido_p,
            'apellido_m' => $request->apellido_m ?? null,
            'celular'    => $request->celular ?? null,
            'activo'     => false, // inactivo hasta que active su cuenta
        ]);

        // Generar token de invitación
        $token = Str::random(64);

        // Crear usuario sin contraseña
        $usuario = Usuario::create([
            'id_persona'             => $persona->id,
            'email'                  => $request->email,
            'pass'                   => Hash::make(Str::random(32)), // pass temporal inutilizable
            'admin'                  => false,
            'invitation_token'       => $token,
            'invitation_expires_at'  => now()->addHours(48),
        ]);

        // Relacionar persona con departamento
        PerDep::create([
            'id_persona' => $persona->id,
            'id_depa'    => $request->id_depa,
            'id_rol'     => $request->id_rol,
            'residente'  => true,
        ]);

        // Armar link y mandar correo
        $link = env('FRONTEND_URL') . '/activar-cuenta?token=' . $token;
        $nombreCompleto = $persona->nombre . ' ' . $persona->apellido_p;

        Mail::to($request->email)->send(new InvitacionResidente($nombreCompleto, $link));

        return response()->json([
            'message' => 'Invitación enviada correctamente a ' . $request->email
        ], 201);
    }

    // Residente activa su cuenta con el token
    public function activate(Request $request) {
        $request->validate([
            'token' => 'required|string',
            'pass'  => 'required|min:6|confirmed', // requiere pass_confirmation
        ]);

        $usuario = Usuario::where('invitation_token', $request->token)->first();

        // Validar que el token existe y no ha expirado
        if (!$usuario || now()->isAfter($usuario->invitation_expires_at)) {
            return response()->json([
                'message' => 'El enlace de invitación es inválido o ha expirado.'
            ], 422);
        }

        // Activar cuenta
        $usuario->update([
            'pass'                   => Hash::make($request->pass),
            'email_verified_at'      => now(),
            'invitation_token'       => null,
            'invitation_expires_at'  => null,
        ]);

        // Activar persona
        $usuario->persona->update(['activo' => true]);

        return response()->json([
            'message' => 'Cuenta activada correctamente. Ya puedes iniciar sesión.'
        ]);
    }

    // Admin reenvía invitación si expiró
    public function resend(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if ($usuario->email_verified_at) {
            return response()->json([
                'message' => 'Este usuario ya tiene su cuenta activa.'
            ], 422);
        }

        $token = Str::random(64);
        $usuario->update([
            'invitation_token'      => $token,
            'invitation_expires_at' => now()->addHours(48),
        ]);

        $persona = $usuario->persona;
        $link = env('FRONTEND_URL') . '/activar-cuenta?token=' . $token;
        $nombreCompleto = $persona->nombre . ' ' . $persona->apellido_p;

        Mail::to($usuario->email)->send(new InvitacionResidente($nombreCompleto, $link));

        return response()->json([
            'message' => 'Invitación reenviada correctamente.'
        ]);
    }
}