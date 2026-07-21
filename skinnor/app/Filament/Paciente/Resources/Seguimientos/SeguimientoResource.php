<?php

namespace App\Filament\Paciente\Resources\Seguimientos;

use App\Filament\Paciente\Resources\Seguimientos\Pages;
use App\Filament\Support\SeguimientoFotos;
use App\Models\Lesion;
use App\Models\Seguimiento;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SeguimientoResource extends Resource
{
    protected static ?string $model = Seguimiento::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;

    public static function getNavigationLabel(): string
    {
        return __('nav.mis_seguimientos');
    }

    public static function getModelLabel(): string
    {
        return __('nav.mis_seguimientos');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.mis_seguimientos');
    }

    public static function getEloquentQuery(): Builder
    {
        $paciente = Auth::user()->paciente;
        $lesionIds = Lesion::where('paciente_id', $paciente?->id)->pluck('id');
        return parent::getEloquentQuery()->whereIn('lesion_id', $lesionIds)->orderBy('fecha', 'desc')->orderBy('hora', 'desc');
    }

    public static function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\TextInput::make('nombre_lesion')
                ->label('Nombre de la lesión')
                ->placeholder('Ej: Lunar en el brazo, mancha en la espalda...')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Select::make('parte_del_cuerpo')
                ->label('Ubicación')
                ->required()
                ->options([
                    'cabeza'           => 'Cabeza',
                    'cara'             => 'Cara',
                    'cuello'           => 'Cuello',
                    'pecho'            => 'Pecho',
                    'espalda'          => 'Espalda',
                    'brazo_derecho'    => 'Brazo derecho',
                    'brazo_izquierdo'  => 'Brazo izquierdo',
                    'mano_derecha'     => 'Mano derecha',
                    'mano_izquierda'   => 'Mano izquierda',
                    'abdomen'          => 'Abdomen',
                    'cadera'           => 'Cadera',
                    'pierna_derecha'   => 'Pierna derecha',
                    'pierna_izquierda' => 'Pierna izquierda',
                    'pie_derecho'      => 'Pie derecho',
                    'pie_izquierdo'    => 'Pie izquierdo',
                    'otro'             => 'Otro',
                ])
                ->searchable(),

            Forms\Components\DatePicker::make('fecha')
                ->label(__('fields.fecha'))
                ->required()
                ->default(now()),

            Forms\Components\TimePicker::make('hora')
                ->label(__('fields.hora'))
                ->required()
                ->default(now())
                ->seconds(false),

            Forms\Components\Textarea::make('nota')
                ->label('Nota (opcional)')
                ->rows(3)
                ->columnSpanFull(),

            SeguimientoFotos::uploadField(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return SeguimientoFotos::configureHistoryTable($table, showLesion: true)
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSeguimientos::route('/'),
            'create' => Pages\CreateSeguimiento::route('/create'),
            'view'   => Pages\ViewSeguimiento::route('/{record}'),
            'edit'   => Pages\EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
