<?php

namespace App\Filament\Admin\Resources\Pacientes;

use App\Models\Paciente;
use App\Models\Profesional;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationLabel(): string
    {
        return __('nav.pacientes');
    }

    public static function getModelLabel(): string
    {
        return __('nav.pacientes');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.pacientes');
    }

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make(__('fields.informacion_personal'))
                ->schema([
                    SpatieMediaLibraryFileUpload::make('foto')
                        ->label(__('fields.foto'))
                        ->collection('foto')
                        ->image()
                        ->imageEditor()
                        ->columnSpanFull(),
                    Forms\Components\Select::make('user_id')
                        ->label(__('fields.usuario'))
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('profesional_id')
                        ->label(__('fields.profesional_asignado'))
                        ->options(
                            Profesional::with('user')
                                ->get()
                                ->mapWithKeys(fn ($p) => [$p->id => $p->user->name.' — '.$p->especialidad])
                        )
                        ->searchable()
                        ->nullable(),
                    Forms\Components\TextInput::make('rut')
                        ->label(__('fields.rut')),
                    Forms\Components\DatePicker::make('fecha_nacimiento')
                        ->label(__('fields.fecha_nacimiento')),
                    Forms\Components\Select::make('genero')
                        ->label(__('fields.genero'))
                        ->options([
                            'masculino' => 'Masculino',
                            'femenino' => 'Femenino',
                            'otro' => 'Otro',
                        ]),
                    Forms\Components\TextInput::make('telefono')
                        ->label(__('fields.telefono'))
                        ->tel(),
                ])->columns(2),

            Section::make(__('fields.contacto_emergencia'))
                ->schema([
                    Forms\Components\TextInput::make('nombre_contacto_emergencia')
                        ->label(__('fields.nombre_contacto_emergencia')),
                    Forms\Components\TextInput::make('telefono_emergencia')
                        ->label(__('fields.telefono_emergencia'))
                        ->tel(),
                ])->columns(2),

            Section::make(__('fields.historial_clinico'))
                ->schema([
                    Forms\Components\Textarea::make('historial_medico')
                        ->label(__('fields.historial_medico'))
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('alergias')
                        ->label(__('fields.alergias'))
                        ->rows(2),
                    Forms\Components\Textarea::make('medicamentos')
                        ->label(__('fields.medicamentos'))
                        ->rows(2),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('foto')
                    ->label(__('fields.foto'))
                    ->collection('foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('tables.paciente'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('profesional.user.name')
                    ->label(__('fields.profesional_asignado'))
                    ->placeholder(__('tables.sin_asignar'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('rut')
                    ->label(__('fields.rut'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('genero')
                    ->label(__('fields.genero'))
                    ->badge(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label(__('fields.telefono')),
                Tables\Columns\TextColumn::make('lesiones_count')
                    ->label(__('tables.lesiones'))
                    ->counts('lesiones'),
            ])
            ->actions([
                ViewAction::make()->label(__('actions.ver')),
                EditAction::make()->label(__('actions.editar')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPacientes::route('/'),
            'create' => Pages\CreatePaciente::route('/create'),
            'view' => Pages\ViewPaciente::route('/{record}'),
            'edit' => Pages\EditPaciente::route('/{record}/edit'),
        ];
    }
}
