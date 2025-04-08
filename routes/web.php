<?php

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Admin\SolicitudController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AtencionController;
use App\Http\Controllers\ApiUserSyncController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\Admin\AnotacionController;
use App\Http\Controllers\Admin\Tipo_problemaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\PrestamoController;

// RUTAS DE ADMINISTRADOR
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD de Usuarios
    Route::resource('user', UserController::class);

    // CRUD de Solicitudes
    Route::resource('solicitud', SolicitudController::class);

    // CRUD de Atenciones (solo para técnicos, se filtran en el controlador)
    Route::resource('atencion', AtencionController::class);

    // CRUD de Anotaciones
    Route::resource('anotacion', AnotacionController::class);

    // Ruta para mostrar las anotaciones de una atención
    Route::get('atencion/{atencion}/anotaciones', [AtencionController::class, 'anotaciones'])
        ->name('atencion.anotaciones');

    // CRUD para Tipo Problema
    Route::resource('tipo_problema', Tipo_problemaController::class);

    // Ruta para rechazar una solicitud (para mantener consistencia en el controlador)
    Route::post('solicitud/{solicitud}/rechazar', [SolicitudController::class, 'rechazar'])
        ->name('solicitud.rechazar');

    Route::get('solicitud/dashboard', [SolicitudController::class, 'dashboard'])->name('solicitud.dashboard');

    // CRUD para Prestamos
    Route::resource('prestamo', PrestamoController::class);

    // Dentro del grupo 'admin' en routes/web.php
Route::post('solicitud/{solicitud}/finalizar', [\App\Http\Controllers\Admin\SolicitudController::class, 'finalizar'])
->name('solicitud.finalizar');

});

// Ruta para servir archivos (para solicitudes, si es necesario)
Route::middleware(['auth', 'verified'])->get('/archivo/{archivo}', [ArchivoController::class, 'mostrar'])
    ->where('archivo', '.*')
    ->name('archivo.mostrar');

// Ruta para sincronizar usuarios (API externa)
// Esta ruta se encarga de consumir el endpoint externo y sincronizar los usuarios en segundo plano,
// según la lógica implementada en ApiUserSyncController@syncUsers.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sync-users', [ApiUserSyncController::class, 'syncUsers'])
        ->name('sync.users');
});

// RUTA HOME Y DASHBOARD
Route::get('', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// RUTAS DE AUTH (configuración de Volt, etc.)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/pdf', function () {
    $pdf = App::make('dompdf.wrapper');
    $pdf->loadHTML('<h1>Hola pdf</h1>');
    return $pdf->stream();
});

require __DIR__.'/auth.php';
