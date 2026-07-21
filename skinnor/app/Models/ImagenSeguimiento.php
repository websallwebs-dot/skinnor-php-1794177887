<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenSeguimiento extends Model
{
    protected $table = 'imagenes_seguimiento';

    protected $fillable = [
        'seguimiento_id',
        'ruta',
        'nombre_original',
    ];

    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class);
    }
}
