<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\admin\SolicitudController;
use App\Http\Controllers\admin\AtencionController;
use App\Http\Controllers\admin\AnotacionController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\ApiUserSyncController;
use App\Models\Solicitud;

// RUTAS DE ADMINISTRADOR
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD de Usuarios (las rutas generadas serán admin.user.index, admin.user.create, etc.)
    Route::resource('user', UserController::class);

    // Otras rutas de administración (solicitudes, atenciones, etc.) se pueden agregar aquí:
    // Route::resource('solicitud', SolicitudController::class);
    // Route::resource('atencion', AtencionController::class);
    // Route::resource('anotacion', AnotacionController::class);
});

// Ruta para sincronizar usuarios manualmente (consume la API externa)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sync-users', [ApiUserSyncController::class, 'syncUsers'])->name('sync.users');
});

// RUTA HOME Y DASHBOARD
Route::get('', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// RUTAS DE AUTH
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
