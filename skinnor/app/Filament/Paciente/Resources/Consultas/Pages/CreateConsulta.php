<?php

namespace App\Filament\Paciente\Resources\Consultas\Pages;

use App\Filament\Paciente\Resources\Consultas\ConsultaResource;
use App\Models\Paciente;
use App\Models\Profesional;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateConsulta extends CreateRecord
{
    protected static string $resource = ConsultaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $paciente = Paciente::firstOrCreate(
            ['user_id' => $user->id],
            [
                'rut' => $user->rut ?? null,
                'telefono' => $user->telefono ?? null,
                'profesional_id' => Profesional::first()?->id,
            ]
        );

        if (! $paciente->profesional_id) {
            $primerProfesional = Profesional::first();
            if ($primerProfesional) {
                $paciente->update(['profesional_id' => $primerProfesional->id]);
            }
        }

        $data['paciente_id'] = $paciente->id;
        $data['profesional_id'] = $paciente->profesional_id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
