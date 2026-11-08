<?php

namespace App\Filament\Paciente\Resources\Lesions\Pages;

use App\Filament\Paciente\Resources\Lesions\LesionResource;
use App\Models\Paciente;
use App\Models\Profesional;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLesion extends CreateRecord
{
    protected static string $resource = LesionResource::class;

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
        $data['paciente_id'] = $paciente->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
