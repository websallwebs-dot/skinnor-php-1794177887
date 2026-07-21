<?php

namespace App\Filament\Superadmin\Resources\ClinicaResource\Pages;

use App\Filament\Superadmin\Resources\ClinicaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClinicas extends ListRecords
{
    protected static string $resource = ClinicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
