<?php

namespace App\Filament\Superadmin\Resources\Domains;

use App\Filament\Superadmin\Resources\Domains\Pages\CreateDomain;
use App\Filament\Superadmin\Resources\Domains\Pages\EditDomain;
use App\Filament\Superadmin\Resources\Domains\Pages\ListDomains;
use App\Filament\Superadmin\Resources\Domains\Schemas\DomainForm;
use App\Filament\Superadmin\Resources\Domains\Tables\DomainsTable;
use Stancl\Tenancy\Database\Models\Domain;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    public static function form(Schema $schema): Schema
    {
        return DomainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DomainsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDomains::route('/'),
            'create' => CreateDomain::route('/create'),
            'edit' => EditDomain::route('/{record}/edit'),
        ];
    }
}
