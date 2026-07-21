<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profesional_id')->nullable()->constrained('profesionales')->nullOnDelete();
            $table->string('foto')->nullable();
            $table->string('rut')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero')->nullable();
            $table->string('telefono')->nullable();
            $table->string('nombre_contacto_emergencia')->nullable();
            $table->string('telefono_emergencia')->nullable();
            $table->text('historial_medico')->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicamentos')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pacientes'); }
};
