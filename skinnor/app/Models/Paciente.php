<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Paciente extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profesional(): BelongsTo
    {
        return $this->belongsTo(Profesional::class);
    }

    public function lesiones(): HasMany
    {
        return $this->hasMany(Lesion::class);
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto')
            ->singleFile();

        $this->addMediaCollection('pacientes_imagenes');
    }
}
