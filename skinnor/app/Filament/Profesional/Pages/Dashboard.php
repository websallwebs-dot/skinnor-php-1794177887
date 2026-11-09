<?php

namespace App\Filament\Profesional\Pages;

use App\Models\Lesion;
use App\Models\Paciente;
use App\Models\Seguimiento;
use App\Models\EvaluacionLesion;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Dashboard extends Page
{
    protected string $view = 'filament.profesional.pages.dashboard';

    public static function getNavigationLabel(): string
    {
        return 'Escritorio';
    }

    public static function getModelLabel(): string
    {
        return 'Escritorio';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Escritorios';
    }

    // Métricas principales - inicializadas por defecto
    public ?int $totalPacientes = 0;
    public ?int $lesionesActivas = 0;
    public ?int $lesionesPendientes = 0;
    public ?int $diasMonitoreo = 0;
    public ?int $consultasPendientes = 0;
    public ?int $seguimientosPendientes = 0;

    // Métricas ABCDE agregadas
    public ?int $totalEvaluaciones = 0;
    public ?int $promedioTDS = 0;
    public ?int $lesionesRiesgoAlto = 0;
    public ?int $lesionesRiesgoMedio = 0;
    public ?int $lesionesRiesgoBajo = 0;

    // Datos para gráficos y listas
    public array $lesionesRecientes = [];
    public array $evaluacionesRecientes = [];
    public array $pacientesAlertas = [];
    public array $distribucionRiesgo = [
        'bajo' => 0,
        'medio' => 0,
        'alto' => 0,
        'muy_alto' => 0,
    ];

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user || ! $user->profesional) {
            return;
        }

        $profesional = $user->profesional;
        $pacienteIds = Paciente::where('profesional_id', $profesional->id)->pluck('id');

        if ($pacienteIds->isEmpty()) {
            return;
        }

        // Conteos principales
        $this->totalPacientes = Paciente::where('profesional_id', $profesional->id)->count();
        $this->lesionesActivas = Lesion::whereIn('paciente_id', $pacienteIds)->where('estado', 'activa')->count();
        $this->lesionesPendientes = Lesion::whereIn('paciente_id', $pacienteIds)
            ->where('estado', 'activa')
            ->whereDoesntHave('seguimientos', function ($query) {
                $query->where('fecha', '>=', now()->subDays(7));
            })->count();

        $this->consultasPendientes = \App\Models\Consulta::whereIn('paciente_id', $pacienteIds)
            ->where('leida', false)->count();

        // Días en monitoreo
        $primeraLesion = Lesion::whereIn('paciente_id', $pacienteIds)
            ->orderBy('fecha_inicio', 'asc')->first();
        if ($primeraLesion) {
            $this->diasMonitoreo = (int) Carbon::parse($primeraLesion->fecha_inicio)->diffInDays(now());
        }

        // Estadísticas de evaluaciones ABCDE
        $evaluaciones = EvaluacionLesion::whereIn('lesion_id', function ($q) use ($pacienteIds) {
            $q->select('id')->from('lesiones')
                ->whereIn('paciente_id', $pacienteIds);
        })->get();

        $this->totalEvaluaciones = $evaluaciones->count();
        if ($this->totalEvaluaciones > 0) {
            $this->promedioTDS = round($evaluaciones->avg('tds_score'), 1);
            $this->lesionesRiesgoAlto = $evaluaciones->where('tds_score', '>=', 5.45)->count();
            $this->lesionesRiesgoMedio = $evaluaciones->whereBetween('tds_score', [4.76, 5.45])->count();
            $this->lesionesRiesgoBajo = $evaluaciones->where('tds_score', '<', 4.75)->count();

            $this->distribucionRiesgo = [
                'bajo' => $this->lesionesRiesgoBajo,
                'medio' => $this->lesionesRiesgoMedio,
                'alto' => $evaluaciones->whereBetween('tds_score', [5.46, 6.5])->count(),
                'muy_alto' => $evaluaciones->where('tds_score', '>', 6.5)->count(),
            ];
        }

        // Lesiones recientes con sus evaluaciones
        $this->lesionesRecientes = Lesion::whereIn('paciente_id', $pacienteIds)
            ->with(['paciente.user', 'evaluaciones'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($lesion) {
                $ultimaEval = $lesion->evaluaciones->first();
                return [
                    'id' => $lesion->id,
                    'nombre' => $lesion->nombre,
                    'parte_del_cuerpo' => $lesion->parte_del_cuerpo ?? '—',
                    'fecha_inicio' => $lesion->fecha_inicio ? Carbon::parse($lesion->fecha_inicio)->format('d/m/Y') : '—',
                    'estado' => $lesion->estado,
                    'paciente_nombre' => $lesion->paciente->user->name ?? '—',
                    'paciente_rut' => $lesion->paciente->rut ?? '—',
                    'tds_score' => $ultimaEval?->tds_score,
                    'clasificacion' => $ultimaEval?->clasificacion,
                    'fecha_evaluacion' => $ultimaEval->fecha_evaluacion ? Carbon::parse($ultimaEval->fecha_evaluacion)->format('d/m/Y') : '—',
                    'seguimientos_count' => $lesion->seguimientos->count(),
                ];
            })->toArray();

        // Pacientes con alertas de alto riesgo
        $this->pacientesAlertas = Lesion::whereIn('paciente_id', $pacienteIds)
            ->where('estado', 'activa')
            ->with('paciente.user', 'evaluaciones')
            ->whereHas('evaluaciones', function ($q) {
                $q->where('tds_score', '>=', 5.45);
            })
            ->whereDoesntHave('seguimientos', function ($q) {
                $q->where('fecha', '>=', now()->subDays(14));
            })->limit(5)->get()->map(function ($lesion) {
                return [
                    'id' => $lesion->id,
                    'nombre' => $lesion->nombre,
                    'paciente_nombre' => $lesion->paciente->user->name ?? '—',
                    'tds_score' => $lesion->evaluaciones->first()?->tds_score ?? 0,
                    'ultima_evaluacion' => $lesion->evaluaciones->first()?->fecha_evaluacion
                        ? Carbon::parse($lesion->evaluaciones->first()->fecha_evaluacion)->format('d/m/Y') : '—',
                    'dias_sin_seguimiento' => $lesion->seguimientos->count() > 0
                        ? Carbon::parse($lesion->seguimientos->max('fecha'))->diffInDays(now())
                        : null,
                ];
            })->toArray();
    }

    public function data(): array
    {
        return [
            'totalPacientes' => $this->totalPacientes,
            'lesionesActivas' => $this->lesionesActivas,
            'lesionesPendientes' => $this->lesionesPendientes,
            'diasMonitoreo' => $this->diasMonitoreo,
            'consultasPendientes' => $this->consultasPendientes,
            'seguimientosPendientes' => $this->seguimientosPendientes,
            'totalEvaluaciones' => $this->totalEvaluaciones,
            'promedioTDS' => $this->promedioTDS,
            'lesionesRiesgoAlto' => $this->lesionesRiesgoAlto,
            'lesionesRiesgoMedio' => $this->lesionesRiesgoMedio,
            'lesionesRiesgoBajo' => $this->lesionesRiesgoBajo,
            'lesionesRecientes' => $this->lesionesRecientes,
            'pacientesAlertas' => $this->pacientesAlertas,
            'distribucionRiesgo' => $this->distribucionRiesgo,
        ];
    }
}
