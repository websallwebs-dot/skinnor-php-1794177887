<?php

namespace App\Filament\Profesional\Resources\Pacientes\Pages;

use App\Filament\Profesional\Resources\Pacientes\PacienteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaciente extends ViewRecord
{
    protected static string $resource = PacienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
