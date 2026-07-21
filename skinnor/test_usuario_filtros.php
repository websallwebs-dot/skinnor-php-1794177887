<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Similares\DatasetSearchService;

try {
    $service = new DatasetSearchService();
    
    // Filtros basados en lo que escribió el usuario:
    // Tipo de lesión (mapeado a dx_type): confocal
    // Edad: 30 - 60
    $filtros = [
        'dx_type' => 'confocal',
        'age' => ['$gte' => 30, '$lte' => 60]
    ];
    
    echo "Consultando con filtros:\n";
    print_r($filtros);
    
    $resultados = $service->buscarPorFiltros($filtros, 5);
    
    echo "Resultados: " . count($resultados) . "\n";
    foreach ($resultados as $r) {
        echo "- ID: " . $r['id'] . ", dx_type: " . ($r['meta']['dx_type'] ?? 'N/A') . ", Edad: " . $r['edad_paciente'] . "\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
