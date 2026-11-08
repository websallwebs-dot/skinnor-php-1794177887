<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\PacientePanelProvider;
use App\Providers\Filament\ProfesionalPanelProvider;
use App\Providers\Filament\SuperadminPanelProvider;
use App\Providers\TenancyServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    PacientePanelProvider::class,
    ProfesionalPanelProvider::class,
    SuperadminPanelProvider::class,
    TenancyServiceProvider::class,
];
