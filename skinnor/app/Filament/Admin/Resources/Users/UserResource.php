<?php

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    public static function getNavigationLabel(): string
    {
        return __('nav.usuarios');
    }

    public static function getModelLabel(): string
    {
        return __('nav.usuarios');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.usuarios');
    }

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('fields.nombre'))
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label(__('fields.email'))
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\Select::make('role')
                ->label(__('fields.rol'))
                ->required()
                ->options([
                    'admin'        => 'Administrador',
                    'profesional'  => 'Profesional',
                    'paciente'     => 'Paciente',
                ]),

            Forms\Components\TextInput::make('password')
                ->label(__('fields.contrasena'))
                ->password()
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->dehydrated(fn($state) => filled($state))
                ->required(fn(string $operation) => $operation === 'create'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('fields.nombre'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('fields.email'))
                    ->searchable(),

                TextColumn::make('role')
                    ->label(__('fields.rol'))
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'superadmin'  => 'warning',
                        'admin'       => 'danger',
                        'profesional' => 'info',
                        'paciente'    => 'success',
                        default       => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label(__('tables.creado'))
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()->label(__('actions.ver')),
                EditAction::make()->label(__('actions.editar')),
                DeleteAction::make()->label(__('actions.eliminar')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view'   => Pages\ViewUser::route('/{record}'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
