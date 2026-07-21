<?php

namespace App\Filament\Profesional\Resources\Consultas;

use App\Filament\Profesional\Resources\Consultas\Pages;
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
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleLeftRight;

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
        $profesional = Auth::user()->profesional;
        return parent::getEloquentQuery()->where('profesional_id', $profesional?->id)->orderBy('created_at', 'desc');
    }

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\Textarea::make('mensaje')
                ->label(__('fields.consulta_paciente'))
                ->disabled()
                ->rows(5),

            Forms\Components\Textarea::make('respuesta')
                ->label(__('fields.tu_respuesta'))
                ->required()
                ->rows(5)
                ->placeholder('Escribe tu respuesta al paciente...'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('paciente.user.name')
                    ->label(__('tables.paciente'))
                    ->searchable()
                    ->sortable(),

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
            ->defaultSort('created_at', 'desc')
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make()
                    ->label(__('fields.responder')),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultas::route('/'),
            'view'  => Pages\ViewConsulta::route('/{record}'),
            'edit'  => Pages\EditConsulta::route('/{record}/edit'),
        ];
    }
}
