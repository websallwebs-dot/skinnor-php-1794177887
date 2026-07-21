<?php

namespace App\Services\Similares;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DatasetSearchService
{
    private ?string $pineconeApiKey;
    private ?string $pineconeIndex;
    private string $gcsBucket;

    public function __construct()
    {
        $this->pineconeApiKey = config('services.pinecone.api_key');
        $this->pineconeIndex  = config('services.pinecone.index');
        $this->gcsBucket = config('services.gcs.bucket', 'dermfoundation-ham10000');
    }

    public function buscarPorFiltros(array $filtros, int $limit = 20): array
    {
        if (!$this->pineconeApiKey || !$this->pineconeIndex) {
            throw new \Exception('Configuración de Pinecone no encontrada');
        }

        try {
            $queryUrl = "https://{$this->pineconeIndex}/query";
            
            // Construir el objeto de filtros de Pinecone correctamente
            $pineconeFilters = [];
            foreach ($filtros as $key => $value) {
                if ($key === 'age' && is_array($value)) {
                    // Generar array de strings para $in para saltar la limitación de $gte/$lte en strings
                    $rangoEdadesStrings = [];
                    $min = (int)($value['$gte'] ?? 0);
                    $max = (int)($value['$lte'] ?? 100);
                    for ($i = $min; $i <= $max; $i++) {
                        $rangoEdadesStrings[] = number_format((float)$i, 1, '.', '');
                    }
                    $pineconeFilters['age'] = ['$in' => $rangoEdadesStrings];
                } elseif (is_array($value)) {
                    $pineconeFilters[$key] = $value;
                } else {
                    $pineconeFilters[$key] = ['$eq' => (string)$value];
                }
            }
            
            $payload = [
                'topK' => $limit,
                'includeMetadata' => true,
                'filter' => $pineconeFilters,
                'vector' => array_fill(0, 6144, 0.0), // Vector dummy requerido por la API
            ];

            Log::info('Consultando Pinecone con filtros', [
                'filtros' => $pineconeFilters,
                'limit' => $limit,
            ]);

            $response = Http::timeout(30)->withHeaders([
                'Api-Key' => $this->pineconeApiKey,
                'Content-Type' => 'application/json',
            ])->post($queryUrl, $payload);

            if ($response->failed()) {
                Log::error("Pinecone Query failed: " . $response->status() . " - " . $response->body());
                throw new \Exception('Error al consultar Pinecone: ' . $response->body());
            }

            $matches = $response->json('matches', []);
            Log::info('Matches encontrados en Pinecone', ['count' => count($matches)]);

            return $this->enriquecerConImagenesLocales($matches);

        } catch (\Exception $e) {
            Log::error("Error en buscarPorFiltros: " . $e->getMessage());
            throw $e;
        }
    }

    private function enriquecerConImagenesLocales(array $matches): array
    {
        $localPaths = [
            'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_2',
            'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1',
        ];

        $diagnosticosMap = [
            'akiec' => 'Queratosis actínica / Carcinoma intraepitelial',
            'bcc'   => 'Carcinoma basocelular',
            'bkl'   => 'Lesiones benignas tipo queratosis',
            'df'    => 'Dermatofibroma',
            'mel'   => 'Melanoma',
            'nv'    => 'Nevos melanocíticos',
            'vasc'  => 'Lesiones vasculares',
        ];

        return array_map(function ($match) use ($localPaths, $diagnosticosMap) {
            $id       = $match['id'];
            $metadata = $match['metadata'] ?? [];

            $foundLocal = null;
            foreach (['jpg','jpeg','png','PNG','JPG'] as $ext) {
                $filename = $id . '.' . $ext;
                foreach ($localPaths as $p) {
                    if (file_exists($p . DIRECTORY_SEPARATOR . $filename)) {
                        $foundLocal = $filename;
                        break 2;
                    }
                }
            }

            if ($foundLocal) {
                $imageUrl = route('local.image', ['filename' => $foundLocal]);
            } else {
                $imageUrl = "https://storage.googleapis.com/{$this->gcsBucket}/{$id}.jpg";
            }

            $dxCode = $metadata['dx'] ?? null;
            $diagnostico = $diagnosticosMap[$dxCode] ?? ($metadata['diagnosis'] ?? 'Desconocido');

            return [
                'id'            => $id,
                'score'         => $match['score'] ?? 0,
                'image_url'     => $imageUrl,
                'diagnostico'   => $diagnostico,
                'tipo_lesion'   => $metadata['dx_type']  ?? '—',
                'dataset'       => $metadata['dataset']  ?? 'HAM10000',
                'edad_paciente' => $metadata['age']      ?? '—',
                'localizacion'  => $metadata['localization'] ?? '—',
                'meta'          => $metadata,
            ];
        }, $matches);
    }
}
