<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Models\Clinica;
use App\Models\Paciente;
use App\Models\Seguimiento;
use Illuminate\Contracts\Console\Kernel;

$c = Clinica::where('id', 'clinica-test')->first();
if (! $c) {
    exit("No tenant\n");
}
tenancy()->initialize($c);

$p = Paciente::first();
if (! $p) {
    exit("No paciente\n");
}

echo 'Paciente ID: '.$p->id."\n";

$seguimientos = Seguimiento::with('lesion')
    ->whereHas('lesion', function ($q) use ($p) {
        $q->where('paciente_id', $p->id);
    })
    ->get();

echo 'Seguimientos encontrados: '.$seguimientos->count()."\n";
foreach ($seguimientos as $seg) {
    echo 'Seg ID: '.$seg->id.', Lesion: '.($seg->lesion ? $seg->lesion->nombre : 'None')."\n";
}
