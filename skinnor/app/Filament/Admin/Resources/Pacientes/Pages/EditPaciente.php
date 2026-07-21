<?php
namespace App\Filament\Admin\Resources\Pacientes\Pages;
use App\Filament\Admin\Resources\Pacientes\PacienteResource;
use Filament\Resources\Pages\EditRecord;
class EditPaciente extends EditRecord
{
    protected static string $resource = PacienteResource::class;
    protected function getHeaderActions(): array { return []; }
}
