<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('profesional_id')->nullable()->constrained('profesionales')->nullOnDelete();
            $table->text('mensaje');
            $table->text('respuesta')->nullable();
            $table->boolean('leida')->default(false);
            $table->datetime('respondida_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('consultas'); }
};
