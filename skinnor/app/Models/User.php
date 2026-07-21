<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    use Notifiable, InteractsWithMedia;

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
            'password'          => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()) {
            'superadmin'   => $this->role === 'superadmin',
            'admin'        => $this->role === 'admin',
            'profesional'  => $this->role === 'profesional',
            'paciente'     => $this->role === 'paciente',
            default        => false,
        };
    }

    public function profesional()
    {
        return $this->hasOne(Profesional::class);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
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
