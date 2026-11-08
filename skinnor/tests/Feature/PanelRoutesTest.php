<?php

use Illuminate\Support\Facades\Route;



it('landing page es accesible sin autenticación', function () {
    $this->get('/')
        ->assertStatus(200);
});

it('página de login es accesible sin autenticación', function () {
    $this->get('/login')
        ->assertStatus(200);
});

it('página de registro es accesible sin autenticación', function () {
    $this->get('/register')
        ->assertStatus(200);
});

it('ruta de panel de paciente existe y requiere autenticación', function () {
    $route = Route::getRoutes()->getByName('filament.paciente.pages.dashboard');
    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain(\Illuminate\Auth\Middleware\Authenticate::class);
});

it('ruta de panel de profesional existe y requiere autenticación', function () {
    $route = Route::getRoutes()->getByName('filament.profesional.pages.dashboard');
    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain(\Illuminate\Auth\Middleware\Authenticate::class);
});

it('ruta de panel de admin existe y requiere autenticación', function () {
    $route = Route::getRoutes()->getByName('filament.admin.pages.dashboard');
    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain(\Illuminate\Auth\Middleware\Authenticate::class);
});

it('ruta de panel de superadmin existe y requiere autenticación', function () {
    $route = Route::getRoutes()->getByName('filament.superadmin.pages.dashboard');
    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain(\Illuminate\Auth\Middleware\Authenticate::class);
});

it('rutas de lesiones de paciente existen y requieren autenticación', function () {
    $route = Route::getRoutes()->getByName('filament.paciente.resources.lesions.index');
    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain(\Illuminate\Auth\Middleware\Authenticate::class);
});

it('rutas de consultas de paciente existen y requieren autenticación', function () {
    $route = Route::getRoutes()->getByName('filament.paciente.resources.consultas.index');
    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain(\Illuminate\Auth\Middleware\Authenticate::class);
});

it('rutas de seguimientos de paciente existen y requieren autenticación', function () {
    $route = Route::getRoutes()->getByName('filament.paciente.resources.seguimientos.index');
    expect($route)->not->toBeNull();
    expect($route->gatherMiddleware())->toContain(\Illuminate\Auth\Middleware\Authenticate::class);
});
