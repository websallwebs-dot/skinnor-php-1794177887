<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesion extends Model
{
    protected $table = 'lesiones';

    protected $fillable = [
        'paciente_id',
        'nombre',
        'parte_del_cuerpo',
        'descripcion',
        'fecha_inicio',
        'estado',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Lesion $lesion) {
            $lesion->seguimientos()->delete();
        });
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class)->orderBy('fecha')->orderBy('hora');
    }
}
