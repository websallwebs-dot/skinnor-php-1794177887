<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Seguimientos;

use App\Filament\Paciente\Resources\Seguimientos\Schemas\SeguimientoForm;
use App\Filament\Paciente\Resources\Seguimientos\Tables\SeguimientosTable;
use App\Models\Lesion;
use App\Models\Paciente;
use App\Models\Seguimiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
        $paciente = Paciente::where('user_id', Auth::id())->first();
        $lesionIds = Lesion::where('paciente_id', $paciente?->id)->pluck('id');

        return parent::getEloquentQuery()
            ->whereIn('lesion_id', $lesionIds)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc');
    }

    public static function form(Schema $form): Schema
    {
        return SeguimientoForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return SeguimientosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeguimientos::route('/'),
            'create' => Pages\CreateSeguimiento::route('/create'),
            'view' => Pages\ViewSeguimiento::route('/{record}'),
            'edit' => Pages\EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
