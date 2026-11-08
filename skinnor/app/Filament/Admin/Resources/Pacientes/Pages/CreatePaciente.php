<?php

namespace App\Filament\Admin\Resources\Pacientes\Pages;

use App\Filament\Admin\Resources\Pacientes\PacienteResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaciente extends CreateRecord
{
    protected static string $resource = PacienteResource::class;
}
