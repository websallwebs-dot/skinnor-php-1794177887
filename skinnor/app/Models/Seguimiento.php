<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Seguimiento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'seguimientos';

    protected $fillable = [
        'lesion_id',
        'fecha',
        'hora',
        'nota',
        'subido_por',
        'profesional_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function lesion(): BelongsTo
    {
        return $this->belongsTo(Lesion::class);
    }

    public function profesional(): BelongsTo
    {
        return $this->belongsTo(Profesional::class);
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenSeguimiento::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('fotos_clinicas')
            ->useDisk(config('filesystems.default', 'public'));
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Conversiones deshabilitadas para evitar errores de permisos en Windows
    }
}
