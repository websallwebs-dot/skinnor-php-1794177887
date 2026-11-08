<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

$apiKey = config('services.pinecone.api_key');
$index = config('services.pinecone.index');
$dummyVector = array_fill(0, 6144, 0.0);

echo "Querying Pinecone for localization: trunk AND dx: bcc...\n";

$response = Http::withHeaders([
    'Api-Key' => $apiKey,
    'Content-Type' => 'application/json',
])->post("https://{$index}/query", [
    'vector' => $dummyVector,
    'topK' => 5,
    'includeMetadata' => true,
    'filter' => [
        '$and' => [
            ['localization' => ['$eq' => 'trunk']],
            ['dx' => ['$eq' => 'bcc']],
        ],
    ],
]);

if ($response->successful()) {
    echo "Success!\n";
    $data = $response->json();
    echo 'Matches found: '.count($data['matches'])."\n";
    if (! empty($data['matches'])) {
        foreach ($data['matches'] as $m) {
            echo 'ID: '.$m['id'].', DX: '.$m['metadata']['dx'].', Loc: '.$m['metadata']['localization']."\n";
        }
    }
} else {
    echo 'Error: '.$response->status()."\n";
    echo $response->body()."\n";
}
