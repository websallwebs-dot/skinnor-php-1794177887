<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

$testImageUrl = 'http://localhost:8000/local-image/ISIC_0029264.jpg';

echo "Probando flujo de Predicción con IA con imagen: ISIC_0029264.jpg\n";
echo "URL de prueba: $testImageUrl\n";

try {
    // Llamada al servicio de predicción en puerto 8003
    $response = Http::timeout(60)->post('http://localhost:8003/predict', [
        'image_url' => $testImageUrl,
    ]);

    if ($response->failed()) {
        echo '¡Error del servicio de IA ('.$response->status().")!\n";
        echo 'Cuerpo: '.$response->body()."\n";
    } else {
        echo "¡Predicción completada!\n";
        print_r($response->json());
    }

} catch (Exception $e) {
    echo "¡ERROR CAPTURADO!\n";
    echo 'Mensaje: '.$e->getMessage()."\n";
}
