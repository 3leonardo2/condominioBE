<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Departamento;
use App\Models\PerDep;

class AdminController extends Controller {

    // Lista todos los residentes con su información completa
    public function listarResidentes() {
        $residentes = Usuario::with(['persona', 'persona.perDep.departamento'])
            ->where('admin', false)
            ->get()
            ->map(function ($usuario) {
                $persona = $usuario->persona;
                $perDep = $persona->perDep->first();

                return [
                    'id'           => $usuario->id,
                    'nombre'       => $persona->nombre . ' ' . $persona->apellido_p,
                    'email'        => $usuario->email,
                    'celular'      => $persona->celular ?? '—',
                    'departamento' => $perDep?->departamento?->depa ?? 'Sin asignar',
                    'estado'       => $this->getEstado($usuario),
                ];
            });

        return response()->json($residentes);
    }

    // Desactivar residente
    public function desactivarResidente($id) {
        $usuario = Usuario::findOrFail($id);
        $usuario->persona->update(['activo' => false]);
        return response()->json(['message' => 'Residente desactivado correctamente']);
    }

    // Reactivar residente
    public function reactivarResidente($id) {
        $usuario = Usuario::findOrFail($id);
        $usuario->persona->update(['activo' => true]);
        return response()->json(['message' => 'Residente reactivado correctamente']);
    }

    // Lista todos los departamentos para el formulario de invitación
    public function listarDepartamentos() {
        $departamentos = Departamento::all(['id', 'depa', 'moroso']);
        return response()->json($departamentos);
    }

    // Lista los roles disponibles
    public function listarRoles() {
        $roles = \App\Models\Rol::all(['id', 'rol']);
        return response()->json($roles);
    }

    private function getEstado(Usuario $usuario): string {
        if (!$usuario->persona->activo) return 'Inactivo';
        if (!$usuario->email_verified_at && $usuario->invitation_token) return 'Invitado';
        return 'Activo';
    }
}