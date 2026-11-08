<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Querying Pinecone with dx: nv and age range 40-60...\n";

// Pinpoint: Pinecone's JSON filter syntax for $gte/$lte
// {"field": {"$gte": val, "$lte": val}}
$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json',
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 5,
    'includeMetadata' => true,
    'filter' => [
        'dx' => ['$eq' => 'nv'],
        'age' => ['$gte' => 40, '$lte' => 60],
    ],
]);

if ($response->successful()) {
    echo "Success!\n";
    $data = $response->json();
    echo 'Matches found: '.count($data['matches'])."\n";
    if (! empty($data['matches'])) {
        foreach ($data['matches'] as $m) {
            echo 'ID: '.$m['id'].', DX: '.$m['metadata']['dx'].', Age: '.$m['metadata']['age']."\n";
        }
    }
} else {
    echo 'Error: '.$response->status()."\n";
    echo $response->body()."\n";
}
