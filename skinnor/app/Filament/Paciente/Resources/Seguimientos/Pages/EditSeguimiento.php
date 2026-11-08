<?php

namespace App\Filament\Paciente\Resources\Seguimientos\Pages;

use App\Filament\Paciente\Resources\Seguimientos\SeguimientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSeguimiento extends EditRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $lesion = $this->record->lesion;
        $data['nombre_lesion'] = $lesion?->nombre;
        $data['parte_del_cuerpo'] = $lesion?->parte_del_cuerpo;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
