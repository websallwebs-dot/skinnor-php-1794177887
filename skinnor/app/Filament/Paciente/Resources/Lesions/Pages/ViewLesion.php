<?php

namespace App\Filament\Paciente\Resources\Lesions\Pages;

use App\Filament\Paciente\Resources\Lesions\LesionResource;
use App\Filament\Paciente\Resources\Seguimientos\SeguimientoResource;
use Filament\Resources\Pages\ViewRecord;

class ViewLesion extends ViewRecord
{
    protected static string $resource = LesionResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $seguimiento = $this->record->seguimientos()
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->first();

        if ($seguimiento) {
            redirect()->to(SeguimientoResource::getUrl('view', ['record' => $seguimiento->id]));
        }
    }
}
