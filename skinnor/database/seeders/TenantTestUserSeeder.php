<?php

namespace Database\Seeders;

use App\Models\Paciente;
use App\Models\Profesional;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantTestUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear el usuario Profesional
        $userProf = User::create([
            'name' => 'Profesional Test',
            'email' => 'prof@skinnor.cl',
            'password' => Hash::make('password'),
            'role' => 'profesional',
        ]);

        Profesional::create([
            'user_id' => $userProf->id,
            'especialidad' => 'Dermatología',
        ]);

        // 2. Crear un usuario Paciente
        $userPac = User::create([
            'name' => 'Paciente Test',
            'email' => 'paciente@skinnor.cl',
            'password' => Hash::make('password'),
            'role' => 'paciente',
        ]);

        Paciente::create([
            'user_id' => $userPac->id,
            'profesional_id' => 1, // Asumimos que el profesional creado es el id 1
        ]);
    }
}
