<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant;

class Clinica extends Tenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'nombre',
            'rut',
            'logo',
            'email',
            'telefono',
            'direccion',
            'pais',
            'ciudad',
            'plan',
            'activa',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id');
    }
}
