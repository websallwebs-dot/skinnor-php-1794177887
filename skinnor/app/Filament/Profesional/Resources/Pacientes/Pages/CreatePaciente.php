<?php

namespace App\Filament\Profesional\Resources\Pacientes\Pages;

use App\Filament\Profesional\Resources\Pacientes\PacienteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePaciente extends CreateRecord
{
    protected static string $resource = PacienteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['profesional_id'] = Auth::user()->profesional?->id;

        return $data;
    }
}
