<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\admin\SolicitudController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\ApiUserSyncController;
use App\Http\Controllers\ArchivoController;

// RUTAS DE ADMINISTRADOR
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD de Usuarios (rutas: admin.user.index, admin.user.create, etc.)
    Route::resource('user', UserController::class);

    // CRUD de Solicitudes (rutas: admin.solicitud.index, admin.solicitud.create, etc.)
    Route::resource('solicitud', SolicitudController::class);

    // Puedes agregar aquí otras rutas de administración si lo necesitas:
    // Route::resource('atencion', AtencionController::class);
    // Route::resource('anotacion', AnotacionController::class);
});

// Ruta para servir archivos (disponible para usuarios autenticados)
Route::middleware(['auth', 'verified'])->get('/archivo/{archivo}', [ArchivoController::class, 'mostrar'])
    ->name('archivo.mostrar');

// Ruta para sincronizar usuarios manualmente (consume la API externa)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sync-users', [ApiUserSyncController::class, 'syncUsers'])->name('sync.users');
});

// RUTA HOME Y DASHBOARD
Route::get('', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

// RUTAS DE AUTH
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
