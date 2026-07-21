<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'consultas';

    protected $fillable = [
        'paciente_id',
        'profesional_id',
        'mensaje',
        'respuesta',
        'leida',
        'respondida_at',
    ];

    protected function casts(): array
    {
        return [
            'leida'          => 'boolean',
            'respondida_at'  => 'datetime',
        ];
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function profesional()
    {
        return $this->belongsTo(Profesional::class);
    }
}
