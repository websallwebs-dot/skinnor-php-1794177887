<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$testImageUrl = 'http://localhost:8000/local-image/ISIC_0029264.jpg';

echo "Probando flujo de Predicción con IA (intentando campo 'file')...\n";

try {
    // Intentar enviando campo 'file' en lugar de 'image_url' basado en el error 422 anterior
    $response = Http::timeout(60)->post('http://localhost:8003/predict', [
        'file' => $testImageUrl
    ]);

    if ($response->failed()) {
        echo "¡Error del servicio de IA (" . $response->status() . ")!\n";
        echo "Cuerpo: " . $response->body() . "\n";
    } else {
        echo "¡Predicción completada!\n";
        print_r($response->json());
    }

} catch (\Exception $e) {
    echo "¡ERROR CAPTURADO!\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
}
