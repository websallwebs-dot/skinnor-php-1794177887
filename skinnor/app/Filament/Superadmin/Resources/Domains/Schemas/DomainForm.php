<?php

namespace App\Filament\Superadmin\Resources\Domains\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('domain')
                    ->label('Dominio')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('ej: clinica1.localhost'),
                Select::make('tenant_id')
                    ->label('Clínica')
                    ->relationship('tenant', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
