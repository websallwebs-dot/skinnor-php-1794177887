<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:8|confirmed',
            'rut'             => 'required|string|max:12',
            'fecha_nacimiento'=> 'required|date',
            'genero'          => 'required|in:masculino,femenino,otro',
            'telefono'        => 'required|string|max:15',
        ]);

        $userData = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'paciente',
            'telefono' => $request->telefono,
        ];

        // Asignar el tenant_id si estamos en un contexto de inquilino
        if (function_exists('tenant') && tenant('id')) {
            $userData['tenant_id'] = tenant('id');
        }

        $user = User::create($userData);

        Paciente::create([
            'user_id'          => $user->id,
            'rut'              => $request->rut,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero'           => $request->genero,
            'telefono'         => $request->telefono,
        ]);

        Auth::login($user);
        return redirect('/paciente');
    }
}
