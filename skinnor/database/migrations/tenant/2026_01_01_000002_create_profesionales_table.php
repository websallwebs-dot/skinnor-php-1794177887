<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profesionales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('foto')->nullable();
            $table->text('bio')->nullable();
            $table->string('telefono')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('numero_licencia')->nullable();
            $table->string('rut')->nullable();
            $table->string('universidad')->nullable();
            $table->integer('anos_experiencia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesionales');
    }
};
