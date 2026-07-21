<?php

namespace App\Filament\Paciente\Resources\Lesions\Pages;

use App\Filament\Paciente\Resources\Lesions\LesionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLesion extends CreateRecord
{
    protected static string $resource = LesionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['paciente_id'] = Auth::user()->paciente->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
