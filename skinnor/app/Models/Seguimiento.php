<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Seguimiento extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'seguimientos';

    protected $fillable = [
        'lesion_id',
        'fecha',
        'hora',
        'nota',
        'subido_por',
        'profesional_id',
    ];

    // Permite que Filament maneje estos campos sin error
    public function getFillable(): array
    {
        return array_merge($this->fillable, ['nombre_lesion', 'parte_del_cuerpo']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Conversiones deshabilitadas temporalmente para evitar errores de permisos en Windows
    }

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (Seguimiento $seguimiento) {
            $lesion = $seguimiento->lesion;
            if ($lesion && $lesion->seguimientos()->count() === 0) {
                $lesion->delete();
            }
        });
    }

    public function lesion()
    {
        return $this->belongsTo(Lesion::class);
    }

    public function profesional()
    {
        return $this->belongsTo(Profesional::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenSeguimiento::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('fotos_clinicas')
            ->useDisk(env('MEDIA_DISK', 'public'));
    }
}
