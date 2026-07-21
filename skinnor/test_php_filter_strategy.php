<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Similares\DatasetSearchService;

try {
    $service = new DatasetSearchService();
    
    // Filtros: dx = nv, Age [30, 60]
    $filtros = [
        'dx' => 'nv',
        'age' => ['$gte' => 30, '$lte' => 60] 
    ];
    
    // Pinecone aparentemente almacena la metadata de age como string "30.0", 
    // pero los operadores $gte/$lte en su API REQURIEREN números.
    // Esto es un conflicto irresoluble con la configuración actual del índice.
    
    // LA SOLUCIÓN: Buscar sin filtro de edad y filtrar en PHP.
    
    $filtrosSinEdad = ['dx' => 'nv'];
    
    echo "Consultando sin filtro de edad y filtrando en PHP...\n";
    $resultados = $service->buscarPorFiltros($filtrosSinEdad, 100);
    
    $filtrados = array_filter($resultados, function($r) {
        $age = (float)($r['edad_paciente'] ?? 0);
        return $age >= 30 && $age <= 60;
    });
    
    echo "Total resultados: " . count($resultados) . "\n";
    echo "Resultados filtrados en PHP: " . count($filtrados) . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
