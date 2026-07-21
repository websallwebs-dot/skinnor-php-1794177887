<?php

namespace App\Filament\Profesional\Resources\Seguimientos\Pages;

use App\Filament\Profesional\Resources\Seguimientos\SeguimientoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSeguimiento extends CreateRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['subido_por']      = 'profesional';
        $data['profesional_id']  = Auth::user()->profesional?->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
