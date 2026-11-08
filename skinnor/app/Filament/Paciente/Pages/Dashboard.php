<?php

namespace App\Filament\Paciente\Pages;

use App\Models\Lesion;
use App\Models\Paciente;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected string $view = 'filament.paciente.pages.dashboard';

    public static function getNavigationLabel(): string
    {
        return __('nav.dashboard');
    }

    public static function getModelLabel(): string
    {
        return __('nav.dashboard');
    }

    public ?int $lesionesActivas = 0;

    public ?int $diasMonitoreo = 0;

    public ?int $lesionesPendientes = 0;

    public $alertasRecientes = [];

    public function mount(): void
    {
        $paciente = Paciente::where('user_id', Auth::id())->first();

        if (! $paciente) {
            return;
        }

        // Contar lesiones activas
        $this->lesionesActivas = Lesion::where('paciente_id', $paciente->id)
            ->where('estado', 'activa')
            ->count();

        // Contar lesiones pendientes de actualización fotográfica (sin seguimiento reciente)
        $this->lesionesPendientes = Lesion::where('paciente_id', $paciente->id)
            ->where('estado', 'activa')
            ->whereDoesntHave('seguimientos', function ($query) {
                $query->where('fecha', '>=', now()->subDays(7));
            })
            ->count();

        // Contar días en monitoreo (desde la primera lesión)
        $primeraLesion = Lesion::where('paciente_id', $paciente->id)
            ->orderBy('fecha_inicio', 'asc')
            ->first();

        if ($primeraLesion) {
            $inicio = new \DateTime($primeraLesion->fecha_inicio);
            $hoy = new \DateTime;
            $this->diasMonitoreo = $inicio->diff($hoy)->days;
        }

        // Alertas recientes (lesiones con seguimientos recientes)
        $this->alertasRecientes = Lesion::where('paciente_id', $paciente->id)
            ->with('paciente.user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }
}
