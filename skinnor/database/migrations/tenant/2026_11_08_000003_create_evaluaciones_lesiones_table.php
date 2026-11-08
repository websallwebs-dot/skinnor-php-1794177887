<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones_lesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesion_id')->constrained('lesiones')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            
            // Puntuaciones individuales ABCDE
            $table->tinyInteger('puntuacion_a')->comment('Asimetría (0-2)');
            $table->tinyInteger('puntuacion_b')->comment('Bordes (0-8)');
            $table->tinyInteger('puntuacion_c')->comment('Color (1-5)');
            $table->tinyInteger('puntuacion_d')->comment('Diámetro (1-5)');
            $table->tinyInteger('puntuacion_e')->comment('Evolución (1-5)');
            
            // Resultado calculado
            $table->decimal('tds_score', 4, 2)->comment('Total Dermoscopy Score');
            $table->string('clasificacion', 20)->comment('benigno, sospechoso, maligno');
            
            // Campos adicionales
            $table->text('observaciones')->nullable();
            $table->date('fecha_evaluacion')->default(now());
            
            $table->timestamps();

            $table->index(['lesion_id', 'fecha_evaluacion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_lesiones');
    }
};
