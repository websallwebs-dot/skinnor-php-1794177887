<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Querying Pinecone for DX: nv (just to check metadata type)...\n";

$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json'
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 1,
    'includeMetadata' => true,
    'filter' => ['dx' => ['$eq' => 'nv']]
]);

if ($response->successful()) {
    $data = $response->json();
    $metadata = $data['matches'][0]['metadata'];
    echo "Age type: " . gettype($metadata['age']) . "\n";
    echo "Age value: " . $metadata['age'] . "\n";
}
