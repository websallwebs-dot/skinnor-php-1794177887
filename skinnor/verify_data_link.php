<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Clinica;
use App\Models\Seguimiento;
use App\Models\Paciente;

$c = Clinica::where('id', 'clinica-test')->first();
tenancy()->initialize($c);

$seg = Seguimiento::find(9);
if ($seg && $seg->lesion) {
    echo "Seguimiento ID 9 belongs to Paciente ID: " . $seg->lesion->paciente_id . "\n";
    
    // Check if the professional is linked
    $paciente = Paciente::find($seg->lesion->paciente_id);
    if ($paciente) {
        echo "Paciente " . $paciente->id . " is linked to Profesional ID: " . $paciente->profesional_id . "\n";
    }
} else {
    echo "Seguimiento 9 not found or no lesion\n";
}
