<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Querying Pinecone for NV with Age as number 40-70...\n";

// Cast metadata age to number implicitly via JSON number type
$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json'
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 5,
    'includeMetadata' => true,
    'filter' => [
        'dx' => ['$eq' => 'nv'],
        'age' => ['$gte' => 40, '$lte' => 70] // Using integers/floats in JSON
    ]
]);

if ($response->successful()) {
    echo "Success!\n";
    $data = $response->json();
    echo "Matches found: " . count($data['matches']) . "\n";
    if (!empty($data['matches'])) {
        foreach($data['matches'] as $m) {
            echo "ID: " . $m['id'] . ", DX: " . $m['metadata']['dx'] . ", Age: " . $m['metadata']['age'] . "\n";
        }
    }
} else {
    echo "Error: " . $response->status() . "\n";
    echo $response->body() . "\n";
}
