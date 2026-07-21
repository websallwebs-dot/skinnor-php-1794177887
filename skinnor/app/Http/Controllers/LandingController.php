<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\User;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    /**
     * Mostrar landing page central
     */
    public function index()
    {
        $clinicas = [];
        $dominios = [];

        // Si el usuario está autenticado, mostrar sus clínicas
        if (auth()->check()) {
            $user = auth()->user();
            
            // Si es superadmin, mostrar todas las clínicas
            if ($user->role === 'superadmin') {
                $clinicas = Clinica::where('activa', true)->get();
            } else if ($user->role === 'admin') {
                // Si es admin, mostrar solo su clínica
                $clinicas = Clinica::whereHas('users', fn($q) => $q->where('user_id', $user->id))
                    ->where('activa', true)
                    ->get();
            }
        }

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
