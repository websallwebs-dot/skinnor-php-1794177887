<?php

namespace App\Filament\Paciente\Resources\Consultas;

use App\Filament\Paciente\Resources\Consultas\Pages;
use App\Models\Consulta;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleLeft;

    public static function getNavigationLabel(): string
    {
        return __('nav.consultas');
    }

    public static function getModelLabel(): string
    {
        return __('nav.consultas');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.consultas');
    }

    public static function getEloquentQuery(): Builder
    {
        $paciente = Auth::user()->paciente;
        return parent::getEloquentQuery()->where('paciente_id', $paciente?->id)->orderBy('created_at', 'desc');
    }

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\Textarea::make('mensaje')
                ->label(__('fields.tu_consulta'))
                ->required()
                ->rows(5)
                ->placeholder('Escribe tu pregunta o consulta al profesional...'),

            Forms\Components\Textarea::make('respuesta')
                ->label(__('fields.respuesta_profesional'))
                ->rows(5)
                ->disabled()
                ->placeholder('Aún sin respuesta'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mensaje')
                    ->label(__('tables.consulta'))
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\IconColumn::make('leida')
                    ->label(__('tables.respondida'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('respondida_at')
                    ->label(__('tables.fecha_respuesta'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Sin respuesta'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tables.enviada'))
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ]);
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'view'   => Pages\ViewConsulta::route('/{record}'),
        ];
    }
}
