<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Clinica;
use App\Models\User;

echo "=== Creando registros en base de datos del tenant ===\n\n";

// Obtener el tenant activo
$domain = DB::table('domains')->where('domain', '127.0.0.1')->first();
$tenant = Clinica::find($domain->tenant_id);
echo "Clínica: " . $tenant->nombre . " (ID: " . $tenant->id . ")\n\n";

// 1. Crear usuario Paciente en la DB central
echo "Creando usuarios en DB central...\n";
$pacienteUser = \App\Models\User::updateOrCreate(
    ['email' => 'paciente@skinnor.cl'],
    [
        'name' => 'Paciente Test',
        'password' => bcrypt('password'),
        'role' => 'paciente',
        'tenant_id' => $tenant->id,
    ]
);
echo "  - Paciente: " . $pacienteUser->email . " (ID: " . $pacienteUser->id . ")\n";

$profesionalUser = User::updateOrCreate(
    ['email' => 'prof@skinnor.cl'],
    [
        'name' => 'Profesional Test',
        'password' => bcrypt('password'),
        'role' => 'profesional',
        'tenant_id' => $tenant->id,
    ]
);
echo "  - Profesional: " . $profesionalUser->email . " (ID: " . $profesionalUser->id . ")\n";

$adminUser = User::updateOrCreate(
    ['email' => 'admin@skinnor.cl'],
    [
        'name' => 'Admin Clínica',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'tenant_id' => $tenant->id,
    ]
);
echo "  - Admin: " . $adminUser->email . " (ID: " . $adminUser->id . ")\n";

// 2. Crear registros en tablas del tenant
echo PHP_EOL . "Creando registros en tablas del tenant...\n";

// Paciente
$paciente = \App\Models\Paciente::updateOrCreate(
    ['user_id' => $pacienteUser->id],
    [
        'profesional_id' => null,
        'rut' => '12345678-9',
        'telefono' => '+56912345678',
        'fecha_nacimiento' => '1990-01-01',
        'genero' => 'masculino',
    ]
);
echo "  - Paciente registro: ID " . $paciente->id . PHP_EOL;

// Profesional
$profesional = \App\Models\Profesional::updateOrCreate(
    ['user_id' => $profesionalUser->id],
    [
        'especialidad' => 'Dermatología',
    ]
);
echo "  - Profesional registro: ID " . $profesional->id . PHP_EOL;

// Actualizar paciente con profesional_id
$paciente->update(['profesional_id' => $profesional->id]);
echo "  - Paciente vinculado a profesional\n";

// Crear una lesión de ejemplo
$lesion = \App\Models\Lesion::create([
    'paciente_id' => $paciente->id,
    'nombre' => 'Lunar en brazo',
    'parte_del_cuerpo' => 'brazo',
    'descripcion' => 'Lunar marrón en el brazo izquierdo',
    'fecha_inicio' => '2024-01-01',
    'estado' => 'activa',
]);
echo "  - Lesión creada: ID " . $lesion->id . " - " . $lesion->nombre . PHP_EOL;

// Crear un seguimiento de ejemplo
$seguimiento = \App\Models\Seguimiento::create([
    'lesion_id' => $lesion->id,
    'profesional_id' => $profesional->id,
    'fecha' => '2024-01-15',
    'hora' => '10:00',
    'nota' => 'Seguimiento inicial',
    'subido_por' => 'paciente',
]);
echo "  - Seguimiento creado: ID " . $seguimiento->id . PHP_EOL;

// Crear una consulta de ejemplo
$consulta = \App\Models\Consulta::create([
    'paciente_id' => $paciente->id,
    'profesional_id' => $profesional->id,
    'mensaje' => 'Consulta sobre lunar en brazo',
    'respuesta' => null,
    'leida' => false,
    'respondida_at' => null,
]);
echo "  - Consulta creada: ID " . $consulta->id . PHP_EOL;

echo PHP_EOL . "✅ Todos los registros creados exitosamente!\n";

echo PHP_EOL . "Resumen de registros en el tenant:\n";
echo "  - Usuarios: " . DB::connection('tenant')->table('users')->count() . " (en central)\n";
echo "  - Pacientes: " . DB::connection('tenant')->table('pacientes')->count() . "\n";
echo "  - Profesionales: " . DB::connection('tenant')->table('profesionales')->count() . "\n";
echo "  - Lesiones: " . DB::connection('tenant')->table('lesiones')->count() . "\n";
echo "  - Consultas: " . DB::connection('tenant')->table('consultas')->count() . "\n";
echo "  - Seguimientos: " . DB::connection('tenant')->table('seguimientos')->count() . "\n";