<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Services\Similares\DatasetSearchService;
use Illuminate\Contracts\Console\Kernel;

try {
    $service = new DatasetSearchService;

    // Filtros relajados
    $filtros = [
        'dx' => 'nv',
        'age' => ['$gte' => 30, '$lte' => 60], // Intentando con números puros otra vez
    ];

    $resultados = $service->buscarPorFiltros($filtros, 5);

    echo 'Resultados: '.count($resultados)."\n";
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
