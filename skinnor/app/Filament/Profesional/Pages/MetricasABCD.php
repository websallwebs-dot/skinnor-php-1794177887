<?php

namespace App\Filament\Profesional\Pages;

use Filament\Pages\Page;
use App\Models\Paciente;
use App\Models\Seguimiento;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class MetricasABCD extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Métricas ABCD';
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';
    protected string $view = 'filament.profesional.pages.metricas-a-b-c-d';

    public ?int $pacienteSeleccionado = null;
    public array $evolucionABCD = [];

    public function form(Schema $form): Schema
    {
        return $form->components([
            Select::make('pacienteSeleccionado')
                ->label('Seleccionar Paciente')
                ->options(Paciente::query()->with('user')->get()->pluck('user.name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn () => $this->procesarEvolucion())
                ->searchable(),
        ]);
    }

    public function procesarEvolucion(): void
    {
        if (!$this->pacienteSeleccionado) return;

        $seguimientos = Seguimiento::with(['media', 'lesion'])
            ->whereHas('lesion', fn($q) => $q->where('paciente_id', $this->pacienteSeleccionado))
            ->orderBy('fecha', 'asc')
            ->get();

        $this->evolucionABCD = [];

        foreach ($seguimientos as $seg) {
            $foto = $seg->getMedia('fotos_clinicas')->first();
            if (!$foto) continue;

            $filename = $foto->file_name;
            $filePath = $foto->getPath();

            foreach (['asimetria', 'bordes', 'color', 'diametro'] as $metrica) {
                try {
                    $response = Http::timeout(60)
                        ->attach('file', file_get_contents($filePath), $filename)
                        ->post('http://localhost:8005/metricas/' . $metrica);
                    
                    if ($response->successful()) {
                        $data = $response->json();
                        $this->evolucionABCD[$metrica][] = [
                            'fecha' => $seg->fecha->format('d/m/Y'),
                            'valor' => $data['valor'],
                            'img'   => 'data:image/png;base64,' . $data['image_base64']
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error("Error calculando métrica $metrica: " . $e->getMessage());
                }
            }
        }
    }
}
