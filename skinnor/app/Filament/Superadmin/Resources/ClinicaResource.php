<?php
namespace App\Filament\Superadmin\Resources;

use App\Models\Clinica;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ClinicaResource extends Resource
{
    protected static ?string $model = Clinica::class;

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make(__('fields.informacion_clinica'))
                ->schema([
                    Forms\Components\FileUpload::make('logo')
                        ->label(__('fields.logo'))
                        ->image()
                        ->imageEditor()
                        ->directory('clinicas/logos')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('id')
                        ->label(__('fields.identificador'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Identificador único de la clínica (sin espacios)'),
                    Forms\Components\TextInput::make('nombre')
                        ->label(__('fields.nombre_clinica'))
                        ->required(),
                    Forms\Components\TextInput::make('rut')
                        ->label(__('fields.rut')),
                    Forms\Components\TextInput::make('email')
                        ->label(__('fields.email'))
                        ->email(),
                    Forms\Components\TextInput::make('telefono')
                        ->label(__('fields.telefono'))
                        ->tel(),
                    Forms\Components\TextInput::make('direccion')
                        ->label(__('fields.direccion'))
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make(__('fields.ubicacion'))
                ->schema([
                    Forms\Components\TextInput::make('pais')
                        ->label(__('fields.pais')),
                    Forms\Components\TextInput::make('ciudad')
                        ->label(__('fields.ciudad')),
                ])->columns(2),

            Section::make(__('fields.suscripcion'))
                ->schema([
                    Forms\Components\Select::make('plan')
                        ->label(__('fields.plan'))
                        ->options([
                            'free'       => 'Free',
                            'pro'        => 'Pro',
                            'enterprise' => 'Enterprise',
                        ])
                        ->required(),
                    Forms\Components\Toggle::make('activa')
                        ->label(__('fields.activa'))
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('fields.logo'))
                    ->circular(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label(__('fields.nombre_clinica'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('fields.email')),
                Tables\Columns\TextColumn::make('pais')
                    ->label(__('fields.pais')),
                Tables\Columns\TextColumn::make('ciudad')
                    ->label(__('fields.ciudad')),
                Tables\Columns\TextColumn::make('plan')
                    ->label(__('fields.plan'))
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'free'       => 'gray',
                        'pro'        => 'info',
                        'enterprise' => 'warning',
                        default      => 'gray',
                    }),
                Tables\Columns\IconColumn::make('activa')
                    ->label(__('fields.activa'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tables.creado'))
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Superadmin\Resources\ClinicaResource\RelationManagers\DomainsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Superadmin\Resources\ClinicaResource\Pages\ListClinicas::route('/'),
            'create' => \App\Filament\Superadmin\Resources\ClinicaResource\Pages\CreateClinica::route('/create'),
            'edit'   => \App\Filament\Superadmin\Resources\ClinicaResource\Pages\EditClinica::route('/{record}/edit'),
        ];
    }
}
