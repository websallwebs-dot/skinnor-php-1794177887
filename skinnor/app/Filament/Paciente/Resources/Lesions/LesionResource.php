<?php

namespace App\Filament\Paciente\Resources\Lesions;

use App\Filament\Paciente\Resources\Lesions\Pages;
use App\Models\Lesion;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class LesionResource extends Resource
{
    protected static ?string $model = Lesion::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Eye;

    public static function getNavigationLabel(): string
    {
        return __('nav.mis_lesiones');
    }

    public static function getModelLabel(): string
    {
        return __('nav.mis_lesiones');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.mis_lesiones');
    }

    public static function getEloquentQuery(): Builder
    {
        $paciente = Auth::user()->paciente;
        return parent::getEloquentQuery()->where('paciente_id', $paciente?->id);
    }

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\TextInput::make('nombre')
                ->label(__('fields.nombre_lesion'))
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('parte_del_cuerpo')
                ->label(__('fields.parte_del_cuerpo'))
                ->required()
                ->options([
                    'cabeza'     => 'Cabeza',
                    'cuello'     => 'Cuello',
                    'pecho'      => 'Pecho',
                    'espalda'    => 'Espalda',
                    'brazo_izq'  => 'Brazo izquierdo',
                    'brazo_der'  => 'Brazo derecho',
                    'abdomen'    => 'Abdomen',
                    'pierna_izq' => 'Pierna izquierda',
                    'pierna_der' => 'Pierna derecha',
                    'pie_izq'    => 'Pie izquierdo',
                    'pie_der'    => 'Pie derecho',
                    'otro'       => 'Otro',
                ]),

            Forms\Components\DatePicker::make('fecha_inicio')
                ->label(__('fields.fecha_inicio'))
                ->required()
                ->default(now()),

            Forms\Components\Textarea::make('descripcion')
                ->label(__('fields.descripcion'))
                ->rows(3),
        ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label(__('tables.lesion'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('parte_del_cuerpo')
                    ->label(__('fields.parte_del_cuerpo'))
                    ->badge(),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label(__('tables.fecha'))
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('seguimientos_count')
                    ->label(__('tables.seguimientos'))
                    ->counts('seguimientos'),
            ])
            ->defaultSort('fecha_inicio', 'desc')
            ->headerActions([])
            ->paginated(false)
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Paciente\Resources\Lesions\RelationManagers\SeguimientosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLesions::route('/'),
            'view'  => Pages\ViewLesion::route('/{record}'),
        ];
    }
}
