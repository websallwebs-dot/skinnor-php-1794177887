<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Consultas\Schemas;

use App\Models\Consulta;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ConsultaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('mensaje')
                    ->label('Mensaje')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('respuesta')
                    ->label('Respuesta del profesional')
                    ->disabled()
                    ->columnSpanFull(),
            ])
            ->model(Consulta::class);
    }
}
