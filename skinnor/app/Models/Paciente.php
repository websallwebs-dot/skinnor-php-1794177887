<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Paciente extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'pacientes';

    protected $fillable = [
        'user_id',
        'profesional_id',
        'fecha_nacimiento',
        'genero',
        'historial_medico',
        'rut',
        'telefono',
        'alergias',
        'medicamentos',
        'foto',
        'nombre_contacto_emergencia',
        'telefono_emergencia',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profesional()
    {
        return $this->belongsTo(Profesional::class);
    }

    public function lesiones()
    {
        return $this->hasMany(Lesion::class);
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
