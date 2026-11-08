<?php

namespace App\Filament\Admin\Resources\Pacientes\Pages;

use App\Filament\Admin\Resources\Pacientes\PacienteResource;
use Filament\Resources\Pages\ListRecords;

class ListPacientes extends ListRecords
{
    protected static string $resource = PacienteResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
