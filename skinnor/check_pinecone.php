<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Similares\CasosSimilaresService;

try {
    $service = new CasosSimilaresService();
    
    // Create a dummy vector of 6144 dimensions (all zeros)
    $dummyVector = array_fill(0, 6144, 0.0);
    
    echo "Querying Pinecone...\n";
    $matches = $service->queryPinecone($dummyVector, 1);
    
    if (empty($matches)) {
        echo "No matches found.\n";
    } else {
        echo "Match found!\n";
        echo "Metadata:\n";
        print_r($matches[0]['metadata']);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
