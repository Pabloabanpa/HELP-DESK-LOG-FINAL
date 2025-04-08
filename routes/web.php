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

/*
|--------------------------------------------------------------------------
| Rutas de Administrador
|--------------------------------------------------------------------------
|
| Estas rutas están protegidas por los middleware 'auth' y 'verified' y tienen el
| prefijo 'admin' y el nombre 'admin.'. Aquí se definen los recursos para Usuarios,
| Solicitudes, Atenciones, Anotaciones, Tipo de Problema y Préstamos, junto con
| rutas adicionales para acciones específicas (rechazar, finalizar, etc.).
|
*/
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // CRUD de Usuarios
        Route::resource('user', UserController::class);

        // CRUD de Solicitudes
        Route::resource('solicitud', SolicitudController::class);

        // CRUD de Atenciones (solo para técnicos; se filtran en el controlador)
        Route::resource('atencion', AtencionController::class);

        // CRUD de Anotaciones
        Route::resource('anotacion', AnotacionController::class);

        // Ruta para mostrar las anotaciones de una atención
        Route::get('atencion/{atencion}/anotaciones', [AtencionController::class, 'anotaciones'])
            ->name('atencion.anotaciones');

        // CRUD para Tipo Problema
        Route::resource('tipo_problema', Tipo_problemaController::class);

        // Ruta para rechazar una solicitud (mantener consistencia en el controlador)
        Route::post('solicitud/{solicitud}/rechazar', [SolicitudController::class, 'rechazar'])
            ->name('solicitud.rechazar');

        // Ruta para mostrar el dashboard de solicitudes
        Route::get('solicitud/dashboard', [SolicitudController::class, 'dashboard'])
            ->name('solicitud.dashboard');

        // CRUD para Préstamos
        Route::resource('prestamo', PrestamoController::class);

        // Ruta para finalizar una solicitud
        Route::post('solicitud/{solicitud}/finalizar', [SolicitudController::class, 'finalizar'])
            ->name('solicitud.finalizar');
    });

/*
|--------------------------------------------------------------------------
| Rutas de Archivos
|--------------------------------------------------------------------------
|
| Ruta para servir archivos (por ejemplo, para solicitudes). Se aplica el middleware
| 'auth' y 'verified', y se permite el uso de cualquier nombre de archivo.
|
*/
Route::middleware(['auth', 'verified'])
    ->get('/archivo/{archivo}', [ArchivoController::class, 'mostrar'])
    ->where('archivo', '.*')
    ->name('archivo.mostrar');

/*
|--------------------------------------------------------------------------
| Ruta para Sincronizar Usuarios (API Externa)
|--------------------------------------------------------------------------
|
| Esta ruta, protegida con 'auth' y 'verified', consume un endpoint externo para
| sincronizar los usuarios en segundo plano. Se invoca el método syncUsers del
| ApiUserSyncController.
|
*/
Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/sync-users', [ApiUserSyncController::class, 'syncUsers'])
            ->name('sync.users');
    });

/*
|--------------------------------------------------------------------------
| Rutas de Home y Dashboard
|--------------------------------------------------------------------------
|
| La ruta base y la vista para el dashboard se definen aquí.
|
*/
Route::get('', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación y Configuración (Volt)
|--------------------------------------------------------------------------
|
| Estas rutas están destinadas a configuraciones del usuario (perfil, contraseña,
| apariencia, etc.) y redireccionan la ruta 'settings' a 'settings/profile'.
|
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

/*
|--------------------------------------------------------------------------
| Ruta para Logout
|--------------------------------------------------------------------------
|
| Esta ruta permite que el usuario cierre sesión. Se utiliza una petición POST y
| se invoca el método 'destroy' del AuthenticatedSessionController.
|
*/
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Ruta para Generar PDF
|--------------------------------------------------------------------------
|
| Ruta simple para generar un PDF usando DomPDF.
|
*/
Route::get('/pdf', function () {
    $pdf = App::make('dompdf.wrapper');
    $pdf->loadHTML('<h1>Hola pdf</h1>');
    return $pdf->stream();
});

/*
|--------------------------------------------------------------------------
| Incluir Rutas de Autenticación
|--------------------------------------------------------------------------
|
| Este archivo contiene las rutas de autenticación generadas por Laravel Breeze,
| Jetstream o la solución que utilices.
|
*/
require __DIR__.'/auth.php';
