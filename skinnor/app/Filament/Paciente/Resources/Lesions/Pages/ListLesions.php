<?php

namespace App\Filament\Paciente\Resources\Lesions\Pages;

use App\Filament\Paciente\Resources\Lesions\LesionResource;
use Filament\Resources\Pages\ListRecords;

class ListLesions extends ListRecords
{
    protected static string $resource = LesionResource::class;

    protected function getHeaderActions(): array
    {
        return []; // sin botón de crear
    }
}
