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
        // 1. Crear o recuperar el usuario Profesional
        $userProf = User::firstOrCreate(
            ['email' => 'prof@skinnor.cl'],
            [
                'name' => 'Profesional Test',
                'password' => Hash::make('password'),
                'role' => 'profesional',
            ]
        );

        $profesional = Profesional::firstOrCreate(
            ['user_id' => $userProf->id],
            ['especialidad' => 'Dermatología']
        );

        // 2. Crear o recuperar un usuario Paciente
        $userPac = User::firstOrCreate(
            ['email' => 'paciente@skinnor.cl'],
            [
                'name' => 'Paciente Test',
                'password' => Hash::make('password'),
                'role' => 'paciente',
            ]
        );

        Paciente::firstOrCreate(
            ['user_id' => $userPac->id],
            ['profesional_id' => $profesional->id]
        );
    }
}
