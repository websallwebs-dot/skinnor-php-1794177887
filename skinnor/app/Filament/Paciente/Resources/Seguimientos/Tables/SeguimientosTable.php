<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Seguimientos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeguimientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                TextColumn::make('hora')
                    ->label('Hora'),

                TextColumn::make('nota')
                    ->label('Nota')
                    ->limit(80),

                TextColumn::make('subido_por')
                    ->label('Subido por')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'profesional' => 'primary',
                        'paciente' => 'secondary',
                    }),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
