<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Querying Pinecone for NV AND Age [30, 60] (as numeric)...\n";

$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json'
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 5,
    'includeMetadata' => true,
    'filter' => [
        'dx' => ['$eq' => 'nv'],
        'age' => ['$gte' => 30, '$lte' => 60]
    ]
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Matches found: " . count($data['matches']) . "\n";
} else {
    echo "Error: " . $response->status() . "\n";
    echo $response->body() . "\n";
}
