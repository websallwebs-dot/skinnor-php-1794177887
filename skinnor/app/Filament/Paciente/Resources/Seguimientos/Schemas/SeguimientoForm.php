<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Seguimientos\Schemas;

use App\Filament\Support\SeguimientoFotos;
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
                    ->placeholder('Ej: Lunar en el brazo, mancha en la espalda...')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->visible(fn ($context): bool => $context === 'create'),

                Select::make('parte_del_cuerpo')
                    ->label('Parte del cuerpo / Ubicación')
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
                    ->default(now())
                    ->maxDate(today()),

                TextInput::make('hora')
                    ->label('Hora')
                    ->default(now()->format('H:i'))
                    ->nullable(),

                Textarea::make('nota')
                    ->label('Nota')
                    ->placeholder('Describe si has notado algún cambio en tamaño, forma o color...')
                    ->nullable()
                    ->columnSpanFull(),

                SeguimientoFotos::uploadField(),
            ])
            ->model(Seguimiento::class);
    }
}
