<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\admin\SolicitudController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\AtencionController;
use App\Http\Controllers\ApiUserSyncController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\admin\AnotacionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// RUTAS DE ADMINISTRADOR
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD de Usuarios
    Route::resource('user', UserController::class);

    // CRUD de Solicitudes
    Route::resource('solicitud', SolicitudController::class);

    // CRUD de Atenciones (solo para técnicos, se filtrarán en el controlador)
    Route::resource('atencion', AtencionController::class);
    // CRUD para anotaciones
    Route::resource('anotacion', AnotacionController::class);
    // Ruta para mostrar las anotaciones de una atención
    Route::get('atencion/{atencion}/anotaciones', [AtencionController::class, 'anotaciones'])
    ->name('atencion.anotaciones');

    Route::post('solicitudes/{solicitud}/rechazar', [SolicitudController::class, 'rechazar'])->name('solicitud.rechazar');


});

// Ruta para servir archivos (para solicitudes, si es necesario)
Route::middleware(['auth', 'verified'])->get('/archivo/{archivo}', [ArchivoController::class, 'mostrar'])
    ->where('archivo', '.*')
    ->name('archivo.mostrar');

// Ruta para sincronizar usuarios (API externa)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sync-users', [ApiUserSyncController::class, 'syncUsers'])->name('sync.users');
});

// RUTA HOME Y DASHBOARD
Route::get('', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

// RUTAS DE AUTH (configuración de Volt, etc.)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});




require __DIR__.'/auth.php';
