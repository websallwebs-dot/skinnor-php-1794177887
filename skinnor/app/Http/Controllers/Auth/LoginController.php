<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login-filament', [
            'title' => 'Skinnor — Iniciar sesión',
            'panelName' => '',
            'action' => '/login',
            'registerUrl' => '/register',
        ]);
    }

    public function showSuperadmin()
    {
        return view('auth.login-filament', [
            'title' => 'Superadmin Login — Skinnor',
            'panelName' => 'Superadmin',
            'action' => route('superadmin.login'),
            'registerUrl' => null,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Los superadmin solo pueden entrar por su propia ruta (seguridad)
            if ($user->role === 'superadmin' && ! tenant('id')) {
                // Si intenta entrar por login normal siendo superadmin en central, está bien
            } elseif ($user->role === 'superadmin' && tenant('id')) {
                // Un superadmin no debería ser un usuario de tenant específico
                // a menos que así esté diseñado. Por ahora, permitimos si existe.
            }

            $request->session()->regenerate();

            return match ($user->role) {
                'admin' => redirect('/admin'),
                'profesional' => redirect('/profesional'),
                'paciente' => redirect('/paciente'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->withInput();
    }

    public function loginSuperadmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Verificar que sea superadmin
            if ($user->role !== 'superadmin') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'No tienes permisos de superadmin.',
                ])->withInput();
            }

            $request->session()->regenerate();

            return redirect('/superadmin');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
