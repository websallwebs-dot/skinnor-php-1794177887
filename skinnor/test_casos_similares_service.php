<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Services\Similares\CasosSimilaresService;
use Illuminate\Contracts\Console\Kernel;

// Simular una URL de imagen válida para prueba
$testImageUrl = 'https://storage.googleapis.com/dermfoundation-ham10000/ISIC_0025559.jpg';

echo "Iniciando prueba de CasosSimilaresService...\n";

try {
    $service = new CasosSimilaresService;

    echo "Llamando a buscarSimilares...\n";
    $resultados = $service->buscarSimilares($testImageUrl, 1);

    if (empty($resultados)) {
        echo "No se encontraron resultados (array vacío).\n";
    } else {
        echo '¡Se encontraron '.count($resultados)." resultados!\n";
        print_r($resultados[0]);
    }
} catch (Exception $e) {
    echo "¡ERROR CAPTURADO!\n";
    echo 'Mensaje: '.$e->getMessage()."\n";
}
