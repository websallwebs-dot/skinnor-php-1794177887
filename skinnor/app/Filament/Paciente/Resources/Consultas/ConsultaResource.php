<?php

declare(strict_types=1);

namespace App\Filament\Paciente\Resources\Consultas;

use App\Filament\Paciente\Resources\Consultas\Schemas\ConsultaForm;
use App\Filament\Paciente\Resources\Consultas\Tables\ConsultasTable;
use App\Models\Consulta;
use App\Models\Paciente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleLeft;

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
        $paciente = Paciente::where('user_id', Auth::id())->first();

        return parent::getEloquentQuery()
            ->where('paciente_id', $paciente?->id)
            ->orderBy('created_at', 'desc');
    }

    public static function form(Schema $form): Schema
    {
        return ConsultaForm::configure($form);
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return ConsultasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'view' => Pages\ViewConsulta::route('/{record}'),
        ];
    }
}
