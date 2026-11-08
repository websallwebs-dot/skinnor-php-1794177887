<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LocalImageController;
use App\Http\Middleware\EnsureSuperadmin;
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
Route::middleware(['auth', EnsureSuperadmin::class])->prefix('domains')->group(function () {
    Route::get('/', [LandingController::class, 'manageDomains'])->name('domains.index');
    Route::post('/', [LandingController::class, 'storeDomain'])->name('domains.store');
    Route::delete('/{domain}', [LandingController::class, 'deleteDomain'])->name('domains.delete');
});

// ========================================
// TENANT ROUTES (clinica1.skinnor.local, etc)
// ========================================

// Login y Registro - SIN middleware de tenancy (autenticación contra DB central)
// Los paneles de Filament ya aplican su propio middleware de tenencia tras el login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro (solo pacientes)
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Local image serving
Route::get('/local-image/{filename}', [LocalImageController::class, 'show'])
    ->name('local.image');
