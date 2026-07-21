<?php
namespace App\Filament\Paciente\Resources\Seguimientos\Pages;

use App\Filament\Paciente\Resources\Seguimientos\SeguimientoResource;
use App\Models\Lesion;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSeguimiento extends CreateRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $paciente = Auth::user()->paciente;

        // Crear lesión automáticamente con el nombre y ubicación dados
        $lesion = Lesion::create([
            'paciente_id'      => $paciente->id,
            'nombre'           => $data['nombre_lesion'],
            'parte_del_cuerpo' => $data['parte_del_cuerpo'] ?? null,
            'fecha_inicio'     => $data['fecha'],
            'estado'           => 'activa',
        ]);

        $data['lesion_id']  = $lesion->id;
        $data['subido_por'] = 'paciente';

        // Limpiar campos virtuales
        unset($data['nombre_lesion'], $data['parte_del_cuerpo']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
