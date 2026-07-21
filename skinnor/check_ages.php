<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Checking ages of NV results...\n";

$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json'
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 20,
    'includeMetadata' => true,
    'filter' => ['dx' => ['$eq' => 'nv']]
]);

if ($response->successful()) {
    $data = $response->json();
    foreach($data['matches'] as $m) {
        echo "Age: " . ($m['metadata']['age'] ?? 'N/A') . "\n";
    }
}
