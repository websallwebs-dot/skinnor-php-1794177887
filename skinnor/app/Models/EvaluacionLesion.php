<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionLesion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_lesiones';

    protected $fillable = [
        'lesion_id',
        'usuario_id',
        'puntuacion_a',
        'puntuacion_b',
        'puntuacion_c',
        'puntuacion_d',
        'puntuacion_e',
        'tds_score',
        'clasificacion',
        'observaciones',
        'fecha_evaluacion',
    ];

    protected $casts = [
        'puntuacion_a' => 'integer',
        'puntuacion_b' => 'integer',
        'puntuacion_c' => 'integer',
        'puntuacion_d' => 'integer',
        'puntuacion_e' => 'integer',
        'tds_score' => 'decimal:2',
        'fecha_evaluacion' => 'date',
    ];

    /**
     * Relación con la lesión evaluada.
     */
    public function lesion(): BelongsTo
    {
        return $this->belongsTo(Lesion::class, 'lesion_id');
    }

    /**
     * Relación con el usuario que realizó la evaluación.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Calcula el Total Dermoscopy Score (TDS) a partir de los componentes ABCDE.
     *
     * Fórmula: TDS = (A × 1.3) + (B × 0.1) + (C × 0.5) + (D × 0.5) + (E × 0.5)
     *
     * Referencia: Stolz et al. (1994) - The ABCD rule of dermatoscopy.
     */
    public static function calcularTDS(int $a, int $b, int $c, int $d, int $e): float
    {
        return round(
            ($a * 1.3) + ($b * 0.1) + ($c * 0.5) + ($d * 0.5) + ($e * 0.5),
            2
        );
    }

    /**
     * Clasifica el riesgo según el puntaje TDS.
     *
     * Umbrales clínicos validados:
     *   < 4.75  -> bajo (benigno)
     *   4.76 - 5.45 -> medio (sospechoso)
     *   > 5.45     -> alto (maligno)
     */
    public static function clasificar(float $tds): string
    {
        if ($tds < 4.75) {
            return 'bajo';
        }

        if ($tds <= 5.45) {
            return 'medio';
        }

        return 'alto';
    }

    /**
     * Calcula automáticamente el TDS y la clasificación antes de guardar.
     */
    protected static function booted(): void
    {
        static::creating(function (EvaluacionLesion $evaluacion) {
            $evaluacion->tds_score = self::calcularTDS(
                $evaluacion->puntuacion_a,
                $evaluacion->puntuacion_b,
                $evaluacion->puntuacion_c,
                $evaluacion->puntuacion_d,
                $evaluacion->puntuacion_e
            );

            $evaluacion->clasificacion = self::clasificar($evaluacion->tds_score);
        });
    }
}
