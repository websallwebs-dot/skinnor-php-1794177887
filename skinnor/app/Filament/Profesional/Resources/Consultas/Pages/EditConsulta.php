<?php

namespace App\Filament\Profesional\Resources\Consultas\Pages;

use App\Filament\Profesional\Resources\Consultas\ConsultaResource;
use Filament\Resources\Pages\EditRecord;

class EditConsulta extends EditRecord
{
    protected static string $resource = ConsultaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['leida']          = true;
        $data['respondida_at']  = now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
