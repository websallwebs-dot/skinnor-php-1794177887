<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Similares\CasosSimilaresService;

// Ruta local de la imagen para la prueba
$filename = 'ISIC_0029264.jpg';
$testImageUrl = 'http://localhost:8000/local-image/' . $filename;

echo "Iniciando prueba de Casos Similares con imagen: $filename\n";
echo "URL de prueba: $testImageUrl\n";

try {
    $service = new CasosSimilaresService();
    
    echo "Llamando a buscarSimilares (esto puede tardar unos segundos mientras la IA procesa)...\n";
    $startTime = microtime(true);
    
    $resultados = $service->buscarSimilares($testImageUrl, 12);
    
    $endTime = microtime(true);
    echo "¡Búsqueda completada en " . round($endTime - $startTime, 2) . " segundos!\n";
    echo "Resultados encontrados: " . count($resultados) . "\n";
    
    foreach ($resultados as $r) {
        echo "- ID: " . $r['id'] . ", DX: " . $r['diagnostico'] . ", Score: " . $r['score'] . "\n";
    }

} catch (\Exception $e) {
    echo "¡ERROR CAPTURADO!\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
}
