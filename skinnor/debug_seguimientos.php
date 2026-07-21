<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Clinica;
use App\Models\Seguimiento;
use App\Models\Paciente;

$c = Clinica::where('id', 'clinica-test')->first();
if (!$c) {
    echo "No tenant found\n";
    exit;
}
tenancy()->initialize($c);

$p = Paciente::first();
if (!$p) {
    echo "No paciente found\n";
    exit;
}

echo "Paciente ID: " . $p->id . "\n";
$count = Seguimiento::whereHas('lesion', function($q) use ($p) {
    $q->where('paciente_id', $p->id);
})->count();

echo "Seguimientos count: " . $count . "\n";
