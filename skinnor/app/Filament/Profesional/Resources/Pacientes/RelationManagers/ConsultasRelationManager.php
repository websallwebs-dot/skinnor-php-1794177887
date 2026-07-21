<?php

namespace App\Filament\Profesional\Resources\Pacientes\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ConsultasRelationManager extends RelationManager
{
    protected static string $relationship = 'consultas';
    protected static ?string $title = 'nav.consultas';

    public function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\Textarea::make('mensaje')
                ->label(__('fields.consulta_paciente'))
                ->disabled()
                ->rows(4),

            Forms\Components\Textarea::make('respuesta')
                ->label(__('fields.tu_respuesta'))
                ->required()
                ->rows(4)
                ->placeholder(__('fields.placeholder_consulta')),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('mensaje')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('mensaje')
                    ->label(__('tables.consulta'))
                    ->limit(60),

                Tables\Columns\IconColumn::make('leida')
                    ->label(__('tables.respondida'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tables.enviada'))
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->headerActions([])
            ->actions([
                \Filament\Actions\ViewAction::make()->label(__('actions.ver')),
                \Filament\Actions\EditAction::make()
                    ->label(__('actions.responder'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['leida']         = true;
                        $data['respondida_at'] = now();
                        return $data;
                    }),
            ]);
    }
}
