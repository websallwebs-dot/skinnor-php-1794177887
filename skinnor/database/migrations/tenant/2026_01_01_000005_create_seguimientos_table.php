<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesion_id')->constrained('lesiones')->cascadeOnDelete();
            $table->foreignId('profesional_id')->nullable()->constrained('profesionales')->nullOnDelete();
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->text('nota')->nullable();
            $table->enum('subido_por', ['profesional', 'paciente'])->default('paciente');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('seguimientos'); }
};
