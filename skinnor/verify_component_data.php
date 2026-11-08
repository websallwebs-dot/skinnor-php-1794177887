<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Filament\Profesional\Pages\Laboratorio;
use Illuminate\Contracts\Console\Kernel;

$p = new Laboratorio;
$res = [
    [
        'id' => '1',
        'url' => 'test',
        'score' => 0.9,
        'diagnostico' => 'a',
        'edad_paciente' => 'b',
        'localizacion' => 'c',
        'tipo_lesion' => 'd',
        'meta' => [],
    ],
];
$p->resultadosSimilares = $res;
echo 'Resultados en propiedad: '.count($p->resultadosSimilares)."\n";
foreach ($p->resultadosSimilares as $r) {
    echo 'ID: '.$r['id'].', DX: '.$r['diagnostico']."\n";
}
