<?php

namespace App\Filament\Profesional\Resources\Pacientes;

use App\Filament\Profesional\Resources\Pacientes\Pages;
use App\Models\Paciente;
use App\Models\User;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    public static function getNavigationLabel(): string
    {
        return __('nav.mis_pacientes');
    }

    public static function getModelLabel(): string
    {
        return __('nav.mis_pacientes');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.mis_pacientes');
    }

    // Solo muestra los pacientes asignados al profesional logueado
    public static function getEloquentQuery(): Builder
    {
        $profesional = Auth::user()->profesional;
        return parent::getEloquentQuery()->where('profesional_id', $profesional?->id);
    }

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\Select::make('user_id')
                ->label(__('fields.nombre'))
                ->options(User::where('role', 'paciente')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('fecha_nacimiento')
                ->label(__('fields.fecha_nacimiento')),

            Forms\Components\Select::make('genero')
                ->label(__('fields.genero'))
                ->options([
                    'masculino' => 'Masculino',
                    'femenino'  => 'Femenino',
                    'otro'      => 'Otro',
                ]),

            Forms\Components\Textarea::make('historial_medico')
                ->label(__('fields.historial_medico'))
                ->rows(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('fields.nombre'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('fields.email'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_nacimiento')
                    ->label(__('fields.fecha_nacimiento'))
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('genero')
                    ->label(__('fields.genero'))
                    ->badge(),

                Tables\Columns\TextColumn::make('lesiones_count')
                    ->label(__('tables.lesiones'))
                    ->counts('lesiones'),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()->label(__('actions.ver')),
                \Filament\Actions\EditAction::make()->label(__('actions.editar')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Profesional\Resources\Pacientes\RelationManagers\LesionesRelationManager::class,
            \App\Filament\Profesional\Resources\Pacientes\RelationManagers\ConsultasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPacientes::route('/'),
            'view'   => Pages\ViewPaciente::route('/{record}'),
            'edit'   => Pages\EditPaciente::route('/{record}/edit'),
        ];
    }
}
