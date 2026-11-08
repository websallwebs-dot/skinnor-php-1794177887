<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Services\Similares\DatasetSearchService;
use Illuminate\Contracts\Console\Kernel;

echo "Probando DatasetSearchService (Búsqueda por metadatos)...\n";

try {
    $service = new DatasetSearchService;

    // Filtro basado en lo que probamos que funciona en test_dx_only.php + rango de edad
    $filtros = [
        'dx' => 'nv',
        'age' => ['$gte' => 30],
    ];

    $resultados = $service->buscarPorFiltros($filtros, 5);

    echo 'Resultados: '.count($resultados)."\n";
    foreach ($resultados as $r) {
        echo '- ID: '.$r['id'].', Diagnóstico: '.$r['diagnostico'].', Edad: '.$r['edad_paciente']."\n";
    }

} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
