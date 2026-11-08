<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('puede cargar la página de login', function () {
    $this->get('/login')
        ->assertStatus(200);
});

it('puede hacer login como paciente', function () {
    $user = User::factory()->paciente()->create([
        'email' => 'paciente@skinnor.cl',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'paciente@skinnor.cl',
        'password' => 'password',
    ]);

    $response->assertStatus(302);
    $this->assertStringContainsString('/paciente', $response->headers->get('Location'));
    $this->assertAuthenticatedAs($user);
});

it('puede hacer login como profesional', function () {
    $user = User::factory()->profesional()->create([
        'email' => 'prof@skinnor.cl',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'prof@skinnor.cl',
        'password' => 'password',
    ]);

    $response->assertStatus(302);
    $this->assertStringContainsString('/profesional', $response->headers->get('Location'));
    $this->assertAuthenticatedAs($user);
});

it('puede hacer login como admin', function () {
    $user = User::factory()->admin()->create([
        'email' => 'admin@skinnor.cl',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@skinnor.cl',
        'password' => 'password',
    ]);

    $response->assertStatus(302);
    $this->assertStringContainsString('/admin', $response->headers->get('Location'));
    $this->assertAuthenticatedAs($user);
});

it('no puede hacer login con credenciales incorrectas', function () {
    User::factory()->paciente()->create([
        'email' => 'paciente@skinnor.cl',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'paciente@skinnor.cl',
        'password' => 'wrong_password',
    ]);

    $response->assertStatus(302);
    $this->assertGuest();
});

it('no puede hacer login con email inexistente', function () {
    $response = $this->post('/login', [
        'email' => 'inexistente@skinnor.cl',
        'password' => 'password',
    ]);

    $response->assertStatus(302);
    $this->assertGuest();
});

it('requiere email y password para hacer login', function () {
    $this->post('/login', [])
        ->assertStatus(302);
    $this->assertGuest();
});

it('solo acepta email válido en login', function () {
    $this->post('/login', [
        'email' => 'no-es-un-email',
        'password' => 'password',
    ])
        ->assertStatus(302);
    $this->assertGuest();
});

it('puede hacer logout', function () {
    $user = User::factory()->paciente()->create([
        'email' => 'paciente@skinnor.cl',
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($user);
    $response = $this->post('/logout');
    $response->assertStatus(302);
    $this->assertGuest();
});

it('solo superadmin puede acceder a /superadmin/login', function () {
    $superadmin = User::factory()->superadmin()->create([
        'email' => 'admin@skinnor.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/superadmin/login', [
        'email' => 'admin@skinnor.com',
        'password' => 'password',
    ]);

    $response->assertStatus(302);
    $this->assertStringContainsString('/superadmin', $response->headers->get('Location'));
    $this->assertAuthenticatedAs($superadmin);
});

it('no puede acceder a /superadmin/login con rol diferente a superadmin', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@skinnor.cl',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/superadmin/login', [
        'email' => 'admin@skinnor.cl',
        'password' => 'password',
    ]);

    $response->assertStatus(302);
    $this->assertGuest();
});
