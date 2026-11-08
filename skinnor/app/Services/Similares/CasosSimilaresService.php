<?php

namespace App\Services\Similares;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CasosSimilaresService
{
    private ?string $pineconeApiKey;

    private ?string $pineconeIndex;

    private ?string $dermFoundationEndpoint;

    private string $gcsBucket;

    public function __construct()
    {
        $this->pineconeApiKey = config('services.pinecone.api_key');
        $this->pineconeIndex = config('services.pinecone.index');
        $this->dermFoundationEndpoint = config('services.dermfoundation.endpoint');
        $this->gcsBucket = config('services.gcs.bucket', 'dermfoundation-ham10000');
    }

    /**
     * Busca casos similares en el dataset HAM10000.
     *
     * @param  string  $imageUrl  URL de la imagen a comparar.
     * @param  int  $topK  Número de resultados.
     */
    public function buscarSimilares(string $imageUrl, int $topK = 12): array
    {
        // 1. Validación previa de accesibilidad de la URL
        $this->validarAccesibilidadUrl($imageUrl);

        try {
            // 2. Llamada a la API de Embedding (DermFoundation)
            $response = Http::timeout(60)->post($this->dermFoundationEndpoint, [
                'image_url' => $imageUrl,
            ]);

            if ($response->failed()) {
                throw new \Exception('DermFoundation API respondió con estado '.$response->status().': '.$response->body());
            }

            $embeddingData = $response->json();
            $vector = $embeddingData['embedding'] ?? null;

            if (! $vector || ! is_array($vector)) {
                throw new \Exception('La API de embedding no retornó un array de vectores válido. Respuesta: '.json_encode($embeddingData));
            }

            // 3. Auditoría crítica de dimensiones
            $dimensionesRecibidas = count($vector);
            Log::info("Auditoría de Vector: Dimensiones recibidas = {$dimensionesRecibidas}");

            if ($dimensionesRecibidas !== 6144) {
                throw new \Exception("¡Discrepancia Crítica de Dimensiones! Tu índice Pinecone ({$this->pineconeIndex}) espera 6144, pero se recibieron {$dimensionesRecibidas}.");
            }

            // 4. Consulta a Pinecone
            $pineconeResponse = $this->queryPinecone($vector, $topK);

            Log::info('Pinecone Match Count: '.count($pineconeResponse));

            return $this->enriquecerConGCS($pineconeResponse);

        } catch (\Exception $e) {
            // Registramos el error completo
            Log::critical('FALLO CRÍTICO en buscarSimilares: '.$e->getMessage(), [
                'image_url' => $imageUrl,
                'trace' => $e->getTraceAsString(),
            ]);

            // Lanzamos la excepción para que Filament la capture
            throw new \Exception('Error en Módulo de Casos Similares: '.$e->getMessage());
        }
    }

    private function validarAccesibilidadUrl($url)
    {
        try {
            $response = Http::timeout(3)->head($url);
            if ($response->failed()) {
                Log::warning('La URL de la imagen no es accesible públicamente: '.$url);
            }
        } catch (\Exception $e) {
            throw new \Exception('La URL de la imagen es inaccesible para el backend: '.$url);
        }
    }

    /**
     * Obtiene el embedding de una imagen usando Google Derm Foundation.
     *
     * @param  string  $imageUrl  URL de la imagen.
     * @return array Vector embedding
     */
    public function getEmbedding(string $imageUrl): array
    {
        Log::info('Solicitando embedding a: '.$this->dermFoundationEndpoint);
        $response = Http::timeout(60)->post($this->dermFoundationEndpoint, [
            'image_url' => $imageUrl,
        ]);

        if ($response->failed()) {
            Log::error('DermFoundation API failed: '.$response->status().' - '.$response->body());

            return [];
        }

        return $response->json('embedding', []);
    }

    /**
     * Consulta Pinecone con un vector embedding.
     *
     * @param  array  $embedding  Vector embedding
     * @param  int  $topK  Número de resultados
     * @return array Matches encontrados
     */
    public function queryPinecone(array $embedding, int $topK): array
    {
        // El index host ya viene completo desde el .env (ej: skin222-pezb0pi.svc.aped-4627-b74a.pinecone.io)
        $url = "https://{$this->pineconeIndex}/query";
        Log::info('Consultando Pinecone en: '.$url);

        $response = Http::timeout(30)->withHeaders([
            'Api-Key' => $this->pineconeApiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'vector' => $embedding,
            'topK' => $topK,
            'includeMetadata' => true,
        ]);

        if ($response->failed()) {
            Log::error('Pinecone Query failed: '.$response->status().' - '.$response->body());

            return [];
        }

        return $response->json('matches', []);
    }

    /**
     * Enriquece matches con URLs locales.
     *
     * @param  array  $matches  Matches de Pinecone
     * @return array Matches enriquecidos
     */
    public function enriquecerConGCS(array $matches): array
    {
        // Local HAM10000 paths strictly
        $localPaths = [
            'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_2',
            'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1',
        ];

        $diagnosticosMap = [
            'akiec' => 'Queratosis actínica / Carcinoma intraepitelial',
            'bcc' => 'Carcinoma basocelular',
            'bkl' => 'Lesiones benignas tipo queratosis',
            'df' => 'Dermatofibroma',
            'mel' => 'Melanoma',
            'nv' => 'Nevos melanocíticos',
            'vasc' => 'Lesiones vasculares',
        ];

        $resultados = [];

        foreach ($matches as $match) {
            $id = $match['id'];
            $metadata = $match['metadata'] ?? [];

            // Buscar imagen en carpetas locales estrictamente
            $foundLocal = null;
            foreach (['jpg', 'jpeg', 'png', 'PNG', 'JPG'] as $ext) {
                $filename = $id.'.'.$ext;
                foreach ($localPaths as $p) {
                    if (file_exists($p.DIRECTORY_SEPARATOR.$filename)) {
                        $foundLocal = $filename;
                        break 2;
                    }
                }
            }

            // Si no se encuentra localmente, omitimos el resultado para asegurar integridad
            if (! $foundLocal) {
                Log::warning('Imagen no encontrada localmente para ID: '.$id);

                continue;
            }

            $imageUrl = route('local.image', ['filename' => $foundLocal]);

            $dxCode = $metadata['dx'] ?? null;
            $diagnostico = $diagnosticosMap[$dxCode] ?? ($metadata['diagnosis'] ?? 'Desconocido');

            $resultados[] = [
                'id' => $id,
                'score' => round($match['score'], 4),
                'image_url' => $imageUrl,
                'diagnostico' => $diagnostico,
                'tipo_lesion' => $metadata['dx_type'] ?? '—',
                'dataset' => $metadata['dataset'] ?? 'HAM10000',
                'edad_paciente' => $metadata['age'] ?? '—',
                'localizacion' => $metadata['localization'] ?? '—',
                'meta' => $metadata,
            ];
        }

        return $resultados;
    }
}
