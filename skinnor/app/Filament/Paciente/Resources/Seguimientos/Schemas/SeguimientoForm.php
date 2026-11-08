<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Seguimientos\Schemas;

use App\Models\Seguimiento;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SeguimientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Campos virtuales para crear la lesión asociada (usados en CreateSeguimiento)
                TextInput::make('nombre_lesion')
                    ->label('Nombre de la lesión')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn ($context): bool => $context === 'create'),

                Select::make('parte_del_cuerpo')
                    ->label('Parte del cuerpo')
                    ->options([
                        'cabeza' => 'Cabeza',
                        'cara' => 'Cara',
                        'cuello' => 'Cuello',
                        'pecho' => 'Pecho',
                        'espalda' => 'Espalda',
                        'brazo' => 'Brazo',
                        'pierna' => 'Pierna',
                        'otro' => 'Otro',
                    ])
                    ->nullable()
                    ->visible(fn ($context): bool => $context === 'create'),

                // Campos del seguimiento
                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required()
                    ->maxDate(today()),

                TextInput::make('hora')
                    ->label('Hora')
                    ->nullable(),

                Textarea::make('nota')
                    ->label('Nota')
                    ->nullable()
                    ->columnSpanFull(),

                Select::make('subido_por')
                    ->label('Subido por')
                    ->options([
                        'profesional' => 'Profesional',
                        'paciente' => 'Paciente',
                    ])
                    ->default('paciente')
                    ->required(),
            ])
            ->model(Seguimiento::class);
    }
}
