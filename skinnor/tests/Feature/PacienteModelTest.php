<?php

use App\Models\Paciente;
use App\Models\Profesional;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function () {
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

    $this->pacienteUser = User::factory()->paciente()->create([
        'email' => 'paciente@test.cl',
        'password' => bcrypt('password'),
    ]);

    $this->paciente = Paciente::create([
        'user_id' => $this->pacienteUser->id,
        'rut' => '12345678-9',
        'telefono' => '+56912345678',
        'fecha_nacimiento' => '1990-01-01',
        'genero' => 'masculino',
    ]);

    $this->profesionalUser = User::factory()->profesional()->create([
        'email' => 'prof@test.cl',
        'password' => bcrypt('password'),
    ]);

    $this->profesional = Profesional::create([
        'user_id' => $this->profesionalUser->id,
        'especialidad' => 'Dermatología',
    ]);

    $this->paciente->update(['profesional_id' => $this->profesional->id]);
});

it('no falla si la tabla profesional del tenant no existe', function () {
    $hadTable = Schema::connection('sqlite')->hasTable('profesionales');

    if ($hadTable) {
        Schema::connection('sqlite')->drop('profesionales');
    }

    $user = User::factory()->profesional()->create([
        'email' => 'sin-profesional@test.cl',
        'password' => bcrypt('password'),
    ]);

    expect($user->profesional)->toBeNull();

    if ($hadTable) {
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
    }
});

it('paciente tiene relación con usuario', function () {
    $this->assertTrue($this->paciente->user->is($this->pacienteUser));
    $this->assertEquals('paciente@test.cl', $this->paciente->user->email);
});

it('paciente tiene relación con profesional', function () {
    $this->assertTrue($this->paciente->profesional->is($this->profesional));
    $this->assertEquals('Dermatología', $this->paciente->profesional->especialidad);
});

it('paciente puede tener múltiples lesiones', function () {
    $lesion1 = $this->paciente->lesiones()->create([
        'nombre' => 'Lunar en brazo',
        'parte_del_cuerpo' => 'brazo',
        'descripcion' => 'Lunar marrón en el brazo izquierdo',
        'fecha_inicio' => '2024-01-01',
        'estado' => 'activa',
    ]);

    $lesion2 = $this->paciente->lesiones()->create([
        'nombre' => 'Mancha en espalda',
        'parte_del_cuerpo' => 'espalda',
        'descripcion' => 'Mancha en la espalda media',
        'fecha_inicio' => '2024-02-01',
        'estado' => 'en_seguimiento',
    ]);

    $this->assertCount(2, $this->paciente->lesiones);
    $this->assertTrue($this->paciente->lesiones->contains($lesion1));
    $this->assertTrue($this->paciente->lesiones->contains($lesion2));
});

it('lesión pertenece a un paciente', function () {
    $lesion = $this->paciente->lesiones()->create([
        'nombre' => 'Lesión de prueba',
        'parte_del_cuerpo' => 'cara',
        'descripcion' => 'Descripción de prueba',
        'fecha_inicio' => '2024-01-01',
        'estado' => 'activa',
    ]);

    $this->assertTrue($lesion->paciente->is($this->paciente));
});

it('lesión puede tener múltiples seguimientos', function () {
    $lesion = $this->paciente->lesiones()->create([
        'nombre' => 'Lesión con seguimientos',
        'parte_del_cuerpo' => 'brazo',
        'descripcion' => 'Descripción',
        'fecha_inicio' => '2024-01-01',
        'estado' => 'activa',
    ]);

    $seguimiento1 = $lesion->seguimientos()->create([
        'fecha' => '2024-01-15',
        'hora' => '10:00',
        'nota' => 'Seguimiento inicial',
        'subido_por' => 'paciente',
    ]);

    $seguimiento2 = $lesion->seguimientos()->create([
        'fecha' => '2024-02-15',
        'hora' => '11:00',
        'nota' => 'Seguimiento posterior',
        'subido_por' => 'paciente',
    ]);

    $this->assertCount(2, $lesion->seguimientos);
    $this->assertTrue($lesion->seguimientos->contains($seguimiento1));
    $this->assertTrue($lesion->seguimientos->contains($seguimiento2));
});

it('seguimiento pertenece a una lesión', function () {
    $lesion = $this->paciente->lesiones()->create([
        'nombre' => 'Lesión padre',
        'parte_del_cuerpo' => 'brazo',
        'descripcion' => 'Descripción',
        'fecha_inicio' => '2024-01-01',
        'estado' => 'activa',
    ]);

    $seguimiento = $lesion->seguimientos()->create([
        'fecha' => '2024-01-15',
        'hora' => '10:00',
        'nota' => 'Nota de prueba',
        'subido_por' => 'paciente',
    ]);

    $this->assertTrue($seguimiento->lesion->is($lesion));
});

it('seguimiento puede tener un profesional asignado', function () {
    $lesion = $this->paciente->lesiones()->create([
        'nombre' => 'Lesión con profesional',
        'parte_del_cuerpo' => 'cara',
        'descripcion' => 'Descripción',
        'fecha_inicio' => '2024-01-01',
        'estado' => 'activa',
    ]);

    $seguimiento = $lesion->seguimientos()->create([
        'fecha' => '2024-01-15',
        'hora' => '10:00',
        'nota' => 'Nota',
        'subido_por' => 'profesional',
        'profesional_id' => $this->profesional->id,
    ]);

    $this->assertTrue($seguimiento->profesional->is($this->profesional));
    $this->assertEquals('profesional', $seguimiento->subido_por);
});

it('paciente puede tener múltiples consultas', function () {
    $consulta1 = $this->paciente->consultas()->create([
        'profesional_id' => $this->profesional->id,
        'mensaje' => 'Consulta sobre lunar',
        'respuesta' => null,
        'leida' => false,
        'respondida_at' => null,
    ]);

    $consulta2 = $this->paciente->consultas()->create([
        'profesional_id' => $this->profesional->id,
        'mensaje' => 'Consulta sobre mancha',
        'respuesta' => 'Respuesta del médico',
        'leida' => true,
        'respondida_at' => now(),
    ]);

    $this->assertCount(2, $this->paciente->consultas);
    $this->assertTrue($this->paciente->consultas->contains($consulta1));
    $this->assertTrue($this->paciente->consultas->contains($consulta2));
});

it('consulta pertenece a un paciente y un profesional', function () {
    $consulta = $this->paciente->consultas()->create([
        'profesional_id' => $this->profesional->id,
        'mensaje' => 'Mensaje de prueba',
        'respuesta' => null,
        'leida' => false,
        'respondida_at' => null,
    ]);

    $this->assertTrue($consulta->paciente->is($this->paciente));
    $this->assertTrue($consulta->profesional->is($this->profesional));
});

it('consulta puede estar leída o no leída', function () {
    $consultaLeida = $this->paciente->consultas()->create([
        'profesional_id' => $this->profesional->id,
        'mensaje' => 'Consulta leída',
        'respuesta' => 'Respuesta',
        'leida' => true,
        'respondida_at' => now(),
    ]);

    $consultaNoLeida = $this->paciente->consultas()->create([
        'profesional_id' => $this->profesional->id,
        'mensaje' => 'Consulta no leída',
        'respuesta' => null,
        'leida' => false,
        'respondida_at' => null,
    ]);

    $this->assertTrue($consultaLeida->leida);
    $this->assertFalse($consultaNoLeida->leida);
    $this->assertNotNull($consultaLeida->respondida_at);
    $this->assertNull($consultaNoLeida->respondida_at);
});
