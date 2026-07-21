<?php

namespace App\Filament\Paciente\Resources\Consultas\Pages;

use App\Filament\Paciente\Resources\Consultas\ConsultaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateConsulta extends CreateRecord
{
    protected static string $resource = ConsultaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $paciente = Auth::user()->paciente;
        $data['paciente_id']    = $paciente->id;
        $data['profesional_id'] = $paciente->profesional_id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
