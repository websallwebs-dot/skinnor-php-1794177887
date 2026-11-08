<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class LandingController extends Controller
{
    /**
     * Mostrar landing page central
     */
    public function index()
    {
        // Si el usuario está autenticado, redirigir a su panel correspondiente
        if (auth()->check()) {
            $user = auth()->user();

            return match ($user->role) {
                'superadmin' => redirect('/superadmin'),
                'admin' => redirect('/admin'),
                'profesional' => redirect('/profesional'),
                'paciente' => redirect('/paciente'),
                default => redirect('/'),
            };
        }

        $clinicas = [];
        $dominios = [];

        // Obtener dominios disponibles
        $dominios = Domain::with('tenant')->get();

        return view('landing', compact('clinicas', 'dominios'));
    }

    /**
     * Mostrar página de gestión de dominios (Superadmin)
     */
    public function manageDomains()
    {
        // Verificar que sea superadmin
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'No autorizado');
        }

        $clinicas = Clinica::all();
        $dominios = Domain::with('tenant')->get();

        return view('domains.manage', compact('clinicas', 'dominios'));
    }

    /**
     * Crear nuevo dominio
     */
    public function storeDomain(Request $request)
    {
        // Verificar que sea superadmin
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'domain' => 'required|string|unique:domains,domain',
        ], [
            'tenant_id.required' => 'Debes seleccionar una clínica',
            'tenant_id.exists' => 'La clínica no existe',
            'domain.required' => 'El dominio es requerido',
            'domain.unique' => 'Este dominio ya está registrado',
        ]);

        try {
            Domain::create($validated);

            return back()->with('success', "✅ Dominio '{$validated['domain']}' creado exitosamente");
        } catch (\Exception $e) {
            return back()->with('error', "❌ Error al crear dominio: {$e->getMessage()}")->withInput();
        }
    }

    /**
     * Eliminar dominio
     */
    public function deleteDomain(Domain $domain)
    {
        // Verificar que sea superadmin
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'No autorizado');
        }

        // No permitir eliminar el último dominio de una clínica
        $clinicaDomains = Domain::where('tenant_id', $domain->tenant_id)->count();
        if ($clinicaDomains <= 1) {
            return back()->with('error', '❌ No puedes eliminar el único dominio de una clínica');
        }

        $domainName = $domain->domain;
        $domain->delete();

        return back()->with('success', "✅ Dominio '{$domainName}' eliminado exitosamente");
    }
}
