<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('parte_del_cuerpo')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->enum('estado', ['activa', 'en_seguimiento', 'resuelta'])->default('activa');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('lesiones'); }
};
