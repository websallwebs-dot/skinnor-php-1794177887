<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Services\Similares\CasosSimilaresService;
use Illuminate\Contracts\Console\Kernel;

$testImagePath = 'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1\\ISIC_0029261.jpg';
$testImageUrl = 'https://storage.googleapis.com/dermfoundation-ham10000/ISIC_0025559.jpg';

echo "Probando flujo de Casos Similares...\n";

try {
    $service = new CasosSimilaresService;
    // Forzar validación de acceso (si el archivo existe localmente para el proceso PHP)
    if (! file_exists($testImagePath)) {
        echo "Error: Archivo de prueba no encontrado en disco: $testImagePath\n";
        exit;
    }

    // Ejecutar búsqueda (esto llamará al servicio de embedding local en puerto 8004)
    $resultados = $service->buscarSimilares($testImageUrl, 3);

    echo "¡Búsqueda completada!\n";
    echo 'Resultados: '.count($resultados)."\n";

    foreach ($resultados as $r) {
        echo '- ID: '.$r['id'].', DX: '.$r['diagnostico'].', Score: '.$r['score']."\n";
    }

} catch (Exception $e) {
    echo "¡ERROR CAPTURADO!\n";
    echo 'Mensaje: '.$e->getMessage()."\n";
    // echo "Trace: " . $e->getTraceAsString() . "\n";
}
