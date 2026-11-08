<?php

namespace App\TenancyDatabaseManagers;

use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class SqliteDatabaseManager implements TenantDatabaseManager
{
    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        $path = database_path('tenant_'.$tenant->getTenantKey().'.sqlite');
        if (! file_exists($path)) {
            file_put_contents($path, '');
        }

        return true;
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        $path = database_path('tenant_'.$tenant->getTenantKey().'.sqlite');
        if (file_exists($path)) {
            unlink($path);
        }

        return true;
    }

    public function databaseExists(string $name): bool
    {
        return file_exists(database_path($name.'.sqlite'));
    }

    public function makeConnectionConfig(array $baseConfig, string $databaseName): array
    {
        $baseConfig['database'] = database_path($databaseName.'.sqlite');

        return $baseConfig;
    }

    public function setConnection(string $connection): void
    {
        // no-op
    }
}
