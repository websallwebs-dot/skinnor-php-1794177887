<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profesional extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'profesionales';

    protected $fillable = [
        'user_id',
        'especialidad',
        'numero_licencia',
        'rut',
        'universidad',
        'anos_experiencia',
        'foto',
        'bio',
        'telefono',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto')
             ->singleFile();
    }
}
