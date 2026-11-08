<?php

namespace App\Filament\Admin\Resources\Profesionales;

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

class ProfesionalResource extends Resource
{
    protected static ?string $model = Profesional::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $slug = 'profesionales';

    public static function getNavigationLabel(): string
    {
        return __('nav.profesionales');
    }

    public static function getModelLabel(): string
    {
        return __('nav.profesionales');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.profesionales');
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
                        ->label(__('fields.nombre'))
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('telefono')
                        ->label(__('fields.telefono'))
                        ->tel(),
                ])->columns(2),

            Section::make(__('fields.informacion_profesional'))
                ->schema([
                    Forms\Components\TextInput::make('especialidad')
                        ->label(__('fields.especialidad'))
                        ->required(),
                    Forms\Components\TextInput::make('numero_licencia')
                        ->label(__('fields.numero_licencia')),
                    Forms\Components\TextInput::make('rut')
                        ->label(__('fields.rut')),
                    Forms\Components\TextInput::make('universidad')
                        ->label(__('fields.universidad')),
                    Forms\Components\TextInput::make('anos_experiencia')
                        ->label(__('fields.anos_experiencia'))
                        ->numeric()
                        ->minValue(0),
                    Forms\Components\Textarea::make('bio')
                        ->label(__('fields.bio'))
                        ->rows(3)
                        ->columnSpanFull(),
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
                    ->label(__('fields.nombre'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('especialidad')
                    ->label(__('fields.especialidad'))
                    ->badge(),
                Tables\Columns\TextColumn::make('numero_licencia')
                    ->label(__('fields.numero_licencia')),
                Tables\Columns\TextColumn::make('telefono')
                    ->label(__('fields.telefono')),
                Tables\Columns\TextColumn::make('pacientes_count')
                    ->label(__('nav.pacientes'))
                    ->counts('pacientes'),
            ])
            ->actions([
                ViewAction::make()->label(__('actions.ver')),
                EditAction::make()->label(__('actions.editar')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfesionales::route('/'),
            'create' => Pages\CreateProfesional::route('/create'),
            'view' => Pages\ViewProfesional::route('/{record}'),
            'edit' => Pages\EditProfesional::route('/{record}/edit'),
        ];
    }
}
