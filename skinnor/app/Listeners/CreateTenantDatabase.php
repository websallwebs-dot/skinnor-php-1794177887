<?php

namespace App\Listeners;

use Illuminate\Support\Facades\File;
use Stancl\Tenancy\Events\TenantCreated;

class CreateTenantDatabase
{
    public function handle(TenantCreated $event): void
    {
        $path = database_path('tenant_'.$event->tenant->id.'.sqlite');
        if (! File::exists($path)) {
            File::put($path, '');
        }
    }
}
