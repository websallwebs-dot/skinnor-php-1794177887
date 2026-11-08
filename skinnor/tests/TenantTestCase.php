<?php

namespace Tests;

use App\Models\Clinica;
use App\Models\Paciente;
use App\Models\Profesional;
use App\Models\Lesion;
use App\Models\Consulta;
use App\Models\Seguimiento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TenantTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear tablas de tenant en la DB central (SQLite in-memory)
        Schema::connection('sqlite')->create('profesionales', function (Blueprint $table) {
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

        Schema::connection('sqlite')->create('pacientes', function (Blueprint $table) {
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

        Schema::connection('sqlite')->create('lesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('parte_del_cuerpo')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->enum('estado', ['activa', 'en_seguimiento', 'resuelta'])->default('activa');
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('profesional_id')->nullable()->constrained('profesionales')->nullOnDelete();
            $table->text('mensaje');
            $table->text('respuesta')->nullable();
            $table->boolean('leida')->default(false);
            $table->datetime('respondida_at')->nullable();
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesion_id')->constrained('lesiones')->cascadeOnDelete();
            $table->foreignId('profesional_id')->nullable()->constrained('profesionales')->nullOnDelete();
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->text('nota')->nullable();
            $table->enum('subido_por', ['profesional', 'paciente'])->default('paciente');
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('imagenes_seguimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seguimiento_id')->constrained('seguimientos')->cascadeOnDelete();
            $table->string('ruta');
            $table->string('nombre_original')->nullable();
            $table->string('tipo')->nullable();
            $table->unsignedBigInteger('tamanio')->nullable();
            $table->timestamps();
        });

        // Forzar conexión sqlite para modelos de tenant
        Lesion::setConnection('sqlite');
        Consulta::setConnection('sqlite');
        Seguimiento::setConnection('sqlite');
        Paciente::setConnection('sqlite');
        Profesional::setConnection('sqlite');
    }

    protected function tearDown(): void
    {
        // Restaurar conexiones originales
        Lesion::setConnection(null);
        Consulta::setConnection(null);
        Seguimiento::setConnection(null);
        Paciente::setConnection(null);
        Profesional::setConnection(null);

        parent::tearDown();
    }
}
