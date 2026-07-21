<?php

namespace App\Filament\Paciente\Resources\Consultas\Pages;

use App\Filament\Paciente\Resources\Consultas\ConsultaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConsulta extends EditRecord
{
    protected static string $resource = ConsultaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
