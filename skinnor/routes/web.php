<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// ========================================
// CENTRAL ROUTES (localhost, 127.0.0.1)
// ========================================

// Landing page - sin middleware de tenancy
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Superadmin login - SIN middleware de tenancy
Route::get('/superadmin/login', [LoginController::class, 'showSuperadmin'])->name('superadmin.login');
Route::post('/superadmin/login', [LoginController::class, 'loginSuperadmin']);

// Domain management (solo para superadmin autenticado)
Route::middleware(['auth', \App\Http\Middleware\EnsureSuperadmin::class])->prefix('domains')->group(function () {
    Route::get('/', [LandingController::class, 'manageDomains'])->name('domains.index');
    Route::post('/', [LandingController::class, 'storeDomain'])->name('domains.store');
    Route::delete('/{domain}', [LandingController::class, 'deleteDomain'])->name('domains.delete');
});

// ========================================
// TENANT ROUTES (clinica1.skinnor.local, etc)
// ========================================

// Login y Registro por Tenant - CON middleware de tenancy
Route::middleware([
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
])->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Registro (solo pacientes)
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Local image serving
Route::get('/local-image/{filename}', [App\Http\Controllers\LocalImageController::class, 'show'])
    ->name('local.image');
