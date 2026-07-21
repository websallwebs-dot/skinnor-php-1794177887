<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Querying Pinecone for all NV age 30-60...\n";

// Fetching enough records to check ages manually in code
$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json'
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 100,
    'includeMetadata' => true,
    'filter' => ['dx' => ['$eq' => 'nv']]
]);

if ($response->successful()) {
    $data = $response->json();
    $count = 0;
    foreach($data['matches'] as $m) {
        $age = (float)($m['metadata']['age'] ?? 0);
        if ($age >= 30 && $age <= 60) {
            $count++;
        }
    }
    echo "Found " . $count . " matching NV in age 30-60 in top 100.\n";
} else {
    echo "Error: " . $response->status() . "\n";
    echo $response->body() . "\n";
}
