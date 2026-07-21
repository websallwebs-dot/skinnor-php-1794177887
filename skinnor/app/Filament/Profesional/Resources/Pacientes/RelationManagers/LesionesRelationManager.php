<?php

namespace App\Filament\Profesional\Resources\Pacientes\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class LesionesRelationManager extends RelationManager
{
    protected static string $relationship = 'lesiones';
    protected static ?string $title = 'tables.lesiones';

    public function form(Schema $form): Schema
    {
        return $form->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label(__('fields.nombre'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('parte_del_cuerpo')
                    ->label(__('fields.cuerpo'))
                    ->badge(),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label(__('fields.desde'))
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('seguimientos_count')
                    ->label(__('tables.seguimientos'))
                    ->counts('seguimientos'),
            ])
            ->headerActions([])
            ->actions([
                \Filament\Actions\ViewAction::make()->label(__('actions.ver')),
            ]);
    }
}
