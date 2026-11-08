<?php

use App\Models\Clinica;
use App\Models\User;

it('puede crear una clínica con nombre y slug', function () {
    $clinica = Clinica::create([
        'slug' => 'clinica-prueba',
        'nombre' => 'Clínica Prueba',
        'plan' => 'enterprise',
        'activa' => true,
    ]);

    $this->assertDatabaseHas('clinicas', [
        'slug' => 'clinica-prueba',
        'nombre' => 'Clínica Prueba',
        'plan' => 'enterprise',
        'activa' => true,
    ]);
});

it('una clínica puede tener múltiples dominios', function () {
    $clinica = Clinica::create([
        'slug' => 'clinica-multi',
        'nombre' => 'Clínica Multi Dominio',
        'plan' => 'enterprise',
        'activa' => true,
    ]);

    $clinica->domains()->create([
        'domain' => 'clinica1.test',
    ]);

    $clinica->domains()->create([
        'domain' => 'clinica2.test',
    ]);

    $this->assertCount(2, $clinica->domains);
    $this->assertTrue($clinica->domains->contains(fn ($d) => $d->domain === 'clinica1.test'));
    $this->assertTrue($clinica->domains->contains(fn ($d) => $d->domain === 'clinica2.test'));
});

it('un usuario pertenece a una clínica a través de tenant_id', function () {
    $clinica = Clinica::create([
        'slug' => 'clinica-usuario',
        'nombre' => 'Clínica de Usuario',
        'plan' => 'enterprise',
        'activa' => true,
    ]);

    $user = User::factory()->paciente()->create([
        'email' => 'usuario@clinica.test',
        'password' => bcrypt('password'),
        'tenant_id' => $clinica->id,
    ]);

    $this->assertEquals($clinica->id, $user->tenant_id);
});

it('diferentes usuarios pueden pertenecer a diferentes clínicas', function () {
    $clinica1 = Clinica::create([
        'slug' => 'clinica-1',
        'nombre' => 'Clínica 1',
        'plan' => 'enterprise',
        'activa' => true,
    ]);

    $clinica2 = Clinica::create([
        'slug' => 'clinica-2',
        'nombre' => 'Clínica 2',
        'plan' => 'enterprise',
        'activa' => true,
    ]);

    $usuario1 = User::factory()->paciente()->create([
        'email' => 'usuario1@test.cl',
        'password' => bcrypt('password'),
        'tenant_id' => $clinica1->id,
    ]);

    $usuario2 = User::factory()->paciente()->create([
        'email' => 'usuario2@test.cl',
        'password' => bcrypt('password'),
        'tenant_id' => $clinica2->id,
    ]);

    $this->assertNotEquals($usuario1->tenant_id, $usuario2->tenant_id);
    $this->assertEquals($clinica1->id, $usuario1->tenant_id);
    $this->assertEquals($clinica2->id, $usuario2->tenant_id);
});
