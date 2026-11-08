<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesion extends Model
{
    use HasFactory;
    protected $table = 'lesiones';

    protected $fillable = [
        'paciente_id',
        'nombre',
        'parte_del_cuerpo',
        'descripcion',
        'fecha_inicio',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'estado' => 'string',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class)->orderBy('fecha', 'desc')->orderBy('hora', 'desc');
    }
}
