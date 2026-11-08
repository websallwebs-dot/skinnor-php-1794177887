<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Lesions;

use App\Filament\Paciente\Resources\Lesions\RelationManagers\SeguimientosRelationManager;
use App\Filament\Paciente\Resources\Lesions\Schemas\LesionForm;
use App\Filament\Paciente\Resources\Lesions\Tables\LesionsTable;
use App\Models\Lesion;
use App\Models\Paciente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
        $paciente = Paciente::where('user_id', Auth::id())->first();

        return parent::getEloquentQuery()->where('paciente_id', $paciente?->id);
    }

    public static function form(Schema $form): Schema
    {
        return LesionForm::configure($form);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return LesionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SeguimientosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLesions::route('/'),
            'view' => Pages\ViewLesion::route('/{record}'),
        ];
    }
}
