<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Lesions\Schemas;

use App\Models\Lesion;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LesionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre de la lesión')
                    ->required()
                    ->maxLength(255),

                Select::make('parte_del_cuerpo')
                    ->label('Parte del cuerpo')
                    ->options([
                        'cara' => 'Cara',
                        'cuello' => 'Cuello',
                        'pecho' => 'Pecho',
                        'espalda' => 'Espalda',
                        'abdomen' => 'Abdomen',
                        'brazo' => 'Brazo',
                        'pierna' => 'Pierna',
                        'otro' => 'Otro',
                    ])
                    ->nullable(),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->nullable()
                    ->columnSpanFull(),

                DatePicker::make('fecha_inicio')
                    ->label('Fecha de inicio')
                    ->nullable(),

                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'activa' => 'Activa',
                        'en_seguimiento' => 'En seguimiento',
                        'resuelta' => 'Resuelta',
                    ])
                    ->default('activa')
                    ->required(),
            ])
            ->model(Lesion::class);
    }
}
