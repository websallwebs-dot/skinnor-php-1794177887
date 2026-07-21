<?php

namespace App\Services\Metricas;

use Illuminate\Support\Facades\Log;

class AbcdeAnalyzer
{
    /**
     * Analiza una imagen usando el script Python de OpenCV.
     *
     * @param string $imagePath Ruta absoluta a la imagen en el sistema de archivos local.
     * @return array
     */
    public function analyze(string $imagePath): array
    {
        $scriptPath = base_path('scripts/abcde_analyzer.py');
        $command = "python " . escapeshellarg($scriptPath) . " " . escapeshellarg($imagePath);

        $output = [];
        $returnVar = 0;

        exec($command, $output, $returnVar);

        $result = json_decode(implode('', $output), true);

        if ($returnVar !== 0 || !$result) {
            Log::error("Error ejecutando abcde_analyzer.py", [
                'command' => $command,
                'output' => $output,
                'returnVar' => $returnVar
            ]);

            return [
                'error' => 'No se pudo procesar la imagen. Verifica que Python y OpenCV estén instalados.',
                'asimetria' => '—',
                'borde' => '—',
                'color' => '—',
                'tds' => '—',
            ];
        }

        return $result;
    }
}
