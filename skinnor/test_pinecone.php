<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$apiKey = 'pcsk_5dso3h_B7b7DFkFB4eVFjUUTKuJHcqdRzG27bT2EH9a47UCwFTb8RdZAw4vrJriHFpgwDw';
$indexHost = 'skin222-pezb0pi.svc.aped-4627-b74a.pinecone.io';

// Crear vector dummy de 6144 dimensiones
$vector = array_fill(0, 6144, 0);

$response = Http::timeout(30)->withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json',
])->post("https://{$indexHost}/query", [
    'topK' => 20,
    'includeMetadata' => true,
    'vector' => $vector,
]);

if ($response->failed()) {
    echo "Error: " . $response->status() . " - " . $response->body() . "\n";
    exit(1);
}

$matches = $response->json('matches', []);

echo "=== Metadata de vectores en Pinecone ===\n\n";

$ages = [];
$dxTypes = [];
$localizations = [];
$datasets = [];

foreach ($matches as $match) {
    $metadata = $match['metadata'] ?? [];
    
    if (isset($metadata['age'])) {
        $ages[] = $metadata['age'];
    }
    if (isset($metadata['dx'])) {
        $dxTypes[$metadata['dx']] = true;
    }
    if (isset($metadata['localization'])) {
        $localizations[$metadata['localization']] = true;
    }
    if (isset($metadata['dataset'])) {
        $datasets[$metadata['dataset']] = true;
    }
    
    echo "ID: " . $match['id'] . "\n";
    echo "Metadata: " . json_encode($metadata, JSON_PRETTY_PRINT) . "\n";
    echo "---\n";
}

echo "\n=== Resumen ===\n";
echo "Edades encontradas: " . implode(', ', array_unique($ages)) . "\n";
echo "Rango de edad: " . (min($ages) ?? 'N/A') . " - " . (max($ages) ?? 'N/A') . "\n";
echo "Tipos de diagnóstico: " . implode(', ', array_keys($dxTypes)) . "\n";
echo "Localizaciones: " . implode(', ', array_keys($localizations)) . "\n";
echo "Datasets: " . implode(', ', array_keys($datasets)) . "\n";
