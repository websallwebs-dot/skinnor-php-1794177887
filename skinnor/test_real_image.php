<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use App\Services\Similares\CasosSimilaresService;
use Illuminate\Contracts\Console\Kernel;

// Esta es la ruta local real de la imagen proporcionada por el usuario
$imagePath = 'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1\\ISIC_0029261.jpg';

// Para el servicio de IA local, necesitamos pasarle una URL accesible.
// Como el servidor FastAPI descarga la imagen, debe ser accesible.
// Si el servidor FastAPI corre en la misma máquina, puede que acepte 'file://'
// o una URL local. Vamos a probar la URL del controlador local que sirve las imágenes.
// Usamos el nombre del archivo para que el controlador local la sirva.
$filename = 'ISIC_0029261.jpg';
$testImageUrl = 'http://localhost:8000/local-image/'.$filename;

echo "Iniciando prueba de Casos Similares con imagen: $filename\n";
echo "URL de prueba: $testImageUrl\n";

try {
    $service = new CasosSimilaresService;

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
}
