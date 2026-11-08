<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Consultas\Tables;

use App\Models\Consulta;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConsultasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mensaje')
                    ->label('Mensaje')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('profesional_id')
                    ->label('Profesional')
                    ->getStateUsing(fn (Consulta $record): string => optional($record->profesional)->user->name ?? 'Sin asignar')
                    ->sortable(),

                TextColumn::make('leida')
                    ->label('Leída')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),

                TextColumn::make('respondida_at')
                    ->label('Respondida')
                    ->dateTime()
                    ->placeholder('Sin respuesta'),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
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
