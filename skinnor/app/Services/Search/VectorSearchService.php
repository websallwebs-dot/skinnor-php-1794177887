<?php

namespace App\Services\Search;

use Illuminate\Support\Facades\Http;

class VectorSearchService
{
    protected string $apiKey;
    protected string $indexUrl;

    public function __construct()
    {
        $this->apiKey = env('PINECONE_APIKEY');
        $this->indexUrl = env('PINECONE_HOST'); // Debes añadir este host en el .env
    }

    public function search(array $vector, int $topK = 12): array
    {
        $response = Http::withHeaders([
            'Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("$this->indexUrl/query", [
            'vector' => $vector,
            'topK' => $topK,
            'includeMetadata' => true,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al buscar en Pinecone: ' . $response->body());
        }

        return $response->json()['matches'];
    }
}
