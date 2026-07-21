<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class EmbeddingClient
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:5000';
    }

    public function getEmbedding(string $imageUrl): array
    {
        $response = Http::post("$this->baseUrl/embed", [
            'image_url' => $imageUrl,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al obtener embeddings de IA: ' . $response->body());
        }

        return $response->json()['embedding'];
    }
}
