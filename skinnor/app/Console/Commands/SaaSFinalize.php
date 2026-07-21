<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Clinica;
use App\Models\User;

class SaaSFinalize extends Command
{
    protected $signature = 'saas:finalize';
    protected $description = 'Finaliza la migración a arquitectura SaaS';

    public function handle()
    {
        $clinica = Clinica::updateOrCreate(
            ['slug' => 'clinica-principal'],
            ['nombre' => 'Clínica Principal', 'plan' => 'enterprise', 'activa' => true]
        );

        $this->info('--- Lista de Usuarios en la DB ---');
        User::all()->each(function ($user) use ($clinica) {
            if ($user->email === 'admin@skinnor.com') {
                $user->role = 'superadmin';
            }
            $user->clinica_id = $clinica->id;
            $user->save();

            $this->line("ID: {$user->id} | Nombre: {$user->name} | Email: {$user->email} | Rol: {$user->role} | Clínica ID: {$user->clinica_id}");
        });

        $this->info('----------------------------------');
        $this->info('Migración SaaS completada con éxito.');
    }
}
