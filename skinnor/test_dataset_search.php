<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Services\Similares\DatasetSearchService;
use Illuminate\Contracts\Console\Kernel;

try {
    $service = new DatasetSearchService;

    // Simular los filtros que se envían desde BuscarDataset
    $filtros = [
        'dx' => 'bcc', // Carcinoma basocelular
        'age' => ['$gte' => 30, '$lte' => 60],
        'dataset' => 'HAM10000',
    ];

    $limit = 5;

    echo "Consultando DatasetSearchService con filtros...\n";
    echo 'Filtros: '.json_encode($filtros)."\n";

    $resultados = $service->buscarPorFiltros($filtros, $limit);

    if (empty($resultados)) {
        echo "No se encontraron resultados con esos filtros.\n";
    } else {
        echo '¡Se encontraron '.count($resultados)." resultados!\n";
        foreach ($resultados as $idx => $res) {
            echo 'Resultado '.($idx + 1).":\n";
            echo '- ID: '.$res['id']."\n";
            echo '- Diagnóstico: '.$res['diagnostico']."\n";
            echo '- Edad: '.$res['edad_paciente']."\n";
            echo '- Localización: '.$res['localizacion']."\n";
            echo "-------------------\n";
        }
    }
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
    echo 'Trace: '.$e->getTraceAsString()."\n";
}
