<?php

namespace App\Filament\Profesional\Pages;

use App\Models\Lesion;
use App\Models\Paciente;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MetricasABCDE extends Page
{
    use InteractsWithTable;

    protected string $view = 'filament.profesional.pages.metricas-abcde';

    public static function getNavigationLabel(): string
    {
        return 'Métricas ABCDE';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-chart-bar';
    }

    protected static ?int $navigationSort = 2;

    public ?int $pacienteSeleccionado = null;
    public ?int $lesionSeleccionadaId = null;

    // Métricas ABCDE
    public ?int $abcde_asimetria = 1;
    public ?int $abcde_bordes = 1;
    public ?int $abcde_color = 1;
    public ?int $abcde_diametro = 1;
    public ?int $abcde_evolucion = 1;

    public ?string $abcde_notas = null;
    public ?string $abcde_resultado = null;
    public ?string $abcde_riesgo = null;

    public function mount(): void
    {
        if (request()->has('paciente')) {
            $this->pacienteSeleccionado = (int) request('paciente');
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (! $this->pacienteSeleccionado) {
                    return Lesion::query()->whereRaw('1 = 0');
                }

                return Lesion::query()->where('paciente_id', $this->pacienteSeleccionado);
            })
            ->columns([
                TextColumn::make('nombre')
                    ->label('Lesión')
                    ->searchable(),

                TextColumn::name('parte_del_cuerpo')
                    ->label('Parte del cuerpo')
                    ->badge(),
            ])
            ->actions([
                \Filament\Actions\Action::make('evaluar')
                    ->label('Evaluar ABCDE')
                    ->icon('heroicon-o-chart-bar')
                    ->color('primary')
                    ->button()
                    ->size('xs')
                    ->action(function ($record) {
                        $this->lesionSeleccionadaId = $record->id;
                    }),
            ]);
    }

    public function calcularAbcde(): void
    {
        $this->validate([
            'abcde_asimetria' => 'required|integer|min:1|max:5',
            'abcde_bordes' => 'required|integer|min:1|max:5',
            'abcde_color' => 'required|integer|min:1|max:5',
            'abcde_diametro' => 'required|integer|min:1|max:5',
            'abcde_evolucion' => 'required|integer|min:1|max:5',
        ]);

        $puntos = [
            'asimetria' => $this->abcde_asimetria ?? 1,
            'bordes' => $this->abcde_bordes ?? 1,
            'color' => $this->abcde_color ?? 1,
            'diametro' => $this->abcde_diametro ?? 1,
            'evolucion' => $this->abcde_evolucion ?? 1,
        ];

        $total = array_sum($puntos);

        if ($total >= 20) {
            $riesgo = 'Muy Alto (Atención prioritaria y biopsia sugerida)';
        } elseif ($total >= 15) {
            $riesgo = 'Alto (Seguimiento dermatoscópico estrecho)';
        } elseif ($total >= 10) {
            $riesgo = 'Moderado';
        } else {
            $riesgo = 'Bajo (Parámetros benignos)';
        }

        $this->abcde_resultado = "Puntuación Total: {$total} / 25";
        $this->abcde_riesgo = $riesgo;

        \Filament\Notifications\Notification::make()
            ->title('Evaluación ABCDE Guardada')
            ->body("{$this->abcde_resultado} — Nivel de Riesgo: {$this->abcde_riesgo}")
            ->success()
            ->send();
    }

    public function getPacientesOptions(): array
    {
        return Paciente::query()
            ->with('user')
            ->get()
            ->mapWithKeys(function ($paciente) {
                $nombre = $paciente->user?->name ?? 'Paciente #' . $paciente->id;
                $rut = $paciente->rut ? " ({$paciente->rut})" : '';

                return [$paciente->id => "{$nombre}{$rut}"];
            })->toArray();
    }
}
