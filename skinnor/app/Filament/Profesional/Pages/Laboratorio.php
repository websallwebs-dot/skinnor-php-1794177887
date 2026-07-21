<?php

namespace App\Filament\Profesional\Pages;

use App\Models\Paciente;
use App\Models\Seguimiento;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class Laboratorio extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-beaker';
    protected string $view = 'filament.profesional.pages.laboratorio';

    public static function getNavigationLabel(): string
    {
        return 'Laboratorio';
    }

    public ?string $tabActiva = null;
    public ?int $selectedMediaId = null;
    public ?int $pacienteSeleccionado = null;
    public array $datosSeguimientos = [];

    public function activarHerramienta(string $tab): void
    {
        $this->tabActiva = $tab;
    }

    public function toggleSelectMedia(int $id, string $url): void
    {
        $this->selectedMediaId = $id;
    }

    public function mount(): void
    {
        $this->cargarSeguimientos();
    }

    public function form(Schema $form): Schema
    {
        return $form->components([
            Select::make('pacienteSeleccionado')
                ->label('Seleccionar paciente')
                ->options($this->getPacientesOptions())
                ->reactive()
                ->afterStateUpdated(fn () => $this->cargarSeguimientos())
                ->searchable(),
        ]);
    }

    protected function cargarSeguimientos(): void
    {
        if (!$this->pacienteSeleccionado) {
            $this->datosSeguimientos = [];
            return;
        }

        $seguimientos = Seguimiento::with(['media', 'lesion'])
            ->whereHas('lesion', fn($q) => $q->where('paciente_id', $this->pacienteSeleccionado))
            ->orderByDesc('fecha')
            ->get();

        $fotosPlanas = [];
        foreach ($seguimientos as $seg) {
            foreach ($seg->getMedia('fotos_clinicas') as $foto) {
                // Obtenemos la URL directamente de Spatie Media Library
                $url = $foto->getUrl();

                $fotosPlanas[] = [
                    'id'    => $seg->id,
                    'fecha' => optional($seg->fecha)->format('d/m/Y') ?? '—',
                    'lesion'=> $seg->lesion?->nombre ?? '—',
                    'url'   => $url,
                ];
            }
        }

        $this->datosSeguimientos = $fotosPlanas;
    }

    protected function getPacientesOptions(): array
    {
        $user = Auth::user();
        if (!$user || !$user->profesional) return [];

        return Paciente::where('profesional_id', $user->profesional->id)
            ->with('user')
            ->get()
            ->pluck('user.name', 'id')
            ->toArray();
    }
}
