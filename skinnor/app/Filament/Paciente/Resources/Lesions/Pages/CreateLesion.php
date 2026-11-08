<?php

namespace App\Filament\Paciente\Resources\Lesions\Pages;

use App\Filament\Paciente\Resources\Lesions\LesionResource;
use App\Models\Paciente;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLesion extends CreateRecord
{
    protected static string $resource = LesionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $paciente = Paciente::where('user_id', Auth::id())->first();
        $data['paciente_id'] = $paciente?->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
