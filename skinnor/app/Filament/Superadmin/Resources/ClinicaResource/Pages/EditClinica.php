<?php

namespace App\Filament\Superadmin\Resources\ClinicaResource\Pages;

use App\Filament\Superadmin\Resources\ClinicaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClinica extends EditRecord
{
    protected static string $resource = ClinicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
