<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Similares\DatasetSearchService;

try {
    $service = new DatasetSearchService();
    
    // Filtros relajados basados en lo que vimos en el otro script
    $filtros = [
        'dx' => 'nv', // Probado que existe en el dataset
        'age' => ['$gte' => 40, '$lte' => 60],
    ];
    
    $limit = 5;

    echo "Consultando Pinecone con filtros relajados...\n";
    echo "Filtros: " . json_encode($filtros) . "\n";
    
    $resultados = $service->buscarPorFiltros($filtros, $limit);
    
    if (empty($resultados)) {
        echo "No se encontraron resultados.\n";
    } else {
        echo "¡Se encontraron " . count($resultados) . " resultados!\n";
        print_r($resultados[0]['meta']);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
