<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Support\Facades\File;

class CreateTenantDatabase
{
    public function handle(TenantCreated $event): void
    {
        $path = database_path('tenant_' . $event->tenant->id . '.sqlite');
        if (!File::exists($path)) {
            File::put($path, '');
        }
    }
}
