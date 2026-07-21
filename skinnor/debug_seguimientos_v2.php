<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Clinica;
use App\Models\Seguimiento;
use App\Models\Paciente;

$c = Clinica::where('id', 'clinica-test')->first();
if (!$c) { die("No tenant\n"); }
tenancy()->initialize($c);

$p = Paciente::first();
if (!$p) { die("No paciente\n"); }

echo "Paciente ID: " . $p->id . "\n";

$seguimientos = Seguimiento::with('lesion')
    ->whereHas('lesion', function($q) use ($p) {
        $q->where('paciente_id', $p->id);
    })
    ->get();

echo "Seguimientos encontrados: " . $seguimientos->count() . "\n";
foreach ($seguimientos as $seg) {
    echo "Seg ID: " . $seg->id . ", Lesion: " . ($seg->lesion ? $seg->lesion->nombre : 'None') . "\n";
}
