<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Services\Similares\CasosSimilaresService;
use Illuminate\Contracts\Console\Kernel;

// Ruta de una imagen real en part_1
$imagePath = 'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1\\ISIC_0029264.jpg';
$testImageUrl = 'http://localhost:8000/local-image/ISIC_0029264.jpg';

echo "Probando flujo de Casos Similares con imagen: ISIC_0029264.jpg\n";

try {
    if (! file_exists($imagePath)) {
        echo "Error: Imagen de prueba no encontrada en: $imagePath\n";
        exit;
    }

    $service = new CasosSimilaresService;

    echo "Iniciando búsqueda...\n";
    $startTime = microtime(true);

    $resultados = $service->buscarSimilares($testImageUrl, 12);

    $endTime = microtime(true);
    echo '¡Búsqueda completada en '.round($endTime - $startTime, 2)." segundos!\n";
    echo 'Resultados encontrados: '.count($resultados)."\n";

    foreach ($resultados as $r) {
        echo '- ID: '.$r['id'].', DX: '.$r['diagnostico'].', Score: '.$r['score']."\n";
    }

} catch (Exception $e) {
    echo "¡ERROR CAPTURADO!\n";
    echo 'Mensaje: '.$e->getMessage()."\n";
}
