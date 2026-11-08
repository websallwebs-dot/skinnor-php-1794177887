<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    use HasFactory, InteractsWithMedia, Notifiable;

    // Los usuarios residen en la DB central, no en el tenant
    protected $connection = 'sqlite';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto',
        'telefono',
        'locale',
        'tenant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'superadmin' => $this->role === 'superadmin',
            'admin' => $this->role === 'admin',
            'profesional' => $this->role === 'profesional',
            'paciente' => $this->role === 'paciente',
            default => false,
        };
    }

    public function getProfesionalAttribute(): ?Profesional
    {
        return $this->resolveTenantScopedModelAttribute(Profesional::class, 'profesionales');
    }

    public function getPacienteAttribute(): ?Paciente
    {
        return $this->resolveTenantScopedModelAttribute(Paciente::class, 'pacientes');
    }

    protected function resolveTenantScopedModelAttribute(string $modelClass, string $table): ?Model
    {
        if (! $this->exists || ! $this->getKey()) {
            return null;
        }

        $connections = array_values(array_unique(array_filter([
            'tenant',
            config('database.default'),
            $this->getConnectionName(),
        ])));

        foreach ($connections as $connection) {
            try {
                if (! Schema::connection($connection)->hasTable($table)) {
                    continue;
                }

                $record = DB::connection($connection)->table($table)->where('user_id', $this->getKey())->first();

                if (! $record) {
                    return null;
                }

                $model = new $modelClass;
                $model->setConnection($connection);

                return $model->newQuery()->find($record->id);
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }

    public function clinica()
    {
        return $this->belongsTo(Clinica::class, 'tenant_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto')
            ->singleFile();
    }
}
