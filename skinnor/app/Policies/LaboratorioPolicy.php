<?php

namespace App\Policies;

use App\Models\User;
use App\Filament\Profesional\Pages\Laboratorio;

class LaboratorioPolicy
{
    /**
     * Determine whether the user can view the page.
     */
    public function view(User $user): bool
    {
        return $user->role === 'profesional';
    }
}
