<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Querying Pinecone directly via HTTP...\n";

$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json'
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 1,
    'includeMetadata' => true,
    'filter' => ['dx' => 'nv'] // Pinecone might not need $eq for simple equality
]);

if ($response->successful()) {
    echo "Success!\n";
    print_r($response->json());
} else {
    echo "Error: " . $response->status() . "\n";
    echo $response->body() . "\n";
}
