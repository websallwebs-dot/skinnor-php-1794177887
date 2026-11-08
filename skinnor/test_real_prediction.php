<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

// Ruta de una imagen real para probar la predicción
$imagePath = 'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1\\ISIC_0029264.jpg';
$filename = 'ISIC_0029264.jpg';

echo "Probando flujo de Predicción con IA (Clasificación BEiT)...\n";
echo "Imagen: $filename\n";

if (! file_exists($imagePath)) {
    echo "Error: Imagen de prueba no encontrada en: $imagePath\n";
    exit;
}

try {
    // Llamada al servicio de clasificación en puerto 8003
    // Enviamos el archivo usando ->attach() para que FastAPI lo reciba como UploadFile
    $response = Http::timeout(60)
        ->attach('file', file_get_contents($imagePath), $filename)
        ->post('http://127.0.0.1:8003/predict');

    if ($response->failed()) {
        echo '¡Error del servicio de IA ('.$response->status().")!\n";
        echo 'Cuerpo: '.$response->body()."\n";
    } else {
        echo "¡Predicción exitosa!\n";
        print_r($response->json());
    }

} catch (Exception $e) {
    echo "¡ERROR CAPTURADO!\n";
    echo 'Mensaje: '.$e->getMessage()."\n";
}
