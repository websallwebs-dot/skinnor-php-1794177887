<?php

namespace App\Filament\Profesional\Resources\Pacientes\Pages;

use App\Filament\Profesional\Resources\Pacientes\PacienteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPacientes extends ListRecords
{
    protected static string $resource = PacienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
