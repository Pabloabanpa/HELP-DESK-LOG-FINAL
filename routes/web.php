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
use App\Http\Controllers\Admin\RoleController;

/*
|--------------------------------------------------------------------------
| Rutas de Administrador
|--------------------------------------------------------------------------
|
| Estas rutas están protegidas por 'auth' y 'verified' y tienen el prefijo 'admin'
| y el nombre 'admin.'. Aquí se definen recursos para Usuarios, Solicitudes, Atenciones,
| Anotaciones, Tipo de Problema y Préstamos, junto con rutas adicionales para acciones.
|
*/
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // CRUD de Usuarios
        Route::resource('user', UserController::class);

        Route::get('solicitud/estadisticas', [SolicitudController::class, 'generarEstadisticas'])
        ->name('solicitud.estadisticas');

        Route::get('solicitud/estadisticas_filtrado', [SolicitudController::class, 'generarEstadisticasFiltrado'])
        ->name('solicitud.estadisticas_filtrado');



        // CRUD de Solicitudes (Listado general)
        Route::resource('solicitud', SolicitudController::class);

        // NUEVA RUTA: Índice de Solicitudes Pendientes (solo pendientes)
        Route::get('solicitud/pendientes', [SolicitudController::class, 'pendientes'])
            ->name('solicitud.pendientes');

        // CRUD de Atenciones (solo para técnicos; se filtran en el controlador)
        Route::resource('atencion', AtencionController::class);

        // CRUD de Anotaciones
        Route::resource('anotacion', AnotacionController::class);

        // Ruta para mostrar las anotaciones de una atención
        Route::get('atencion/{atencion}/anotaciones', [AtencionController::class, 'anotaciones'])
            ->name('atencion.anotaciones');

        // CRUD para Tipo Problema
        Route::resource('tipo_problema', Tipo_problemaController::class);

        // Ruta para rechazar una solicitud (mantener consistencia)
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

        // Ruta para reenviar solicitudes pendientes con 7+ días
        Route::post('solicitud/reenviar-pendientes', [SolicitudController::class, 'reenviarPendientes'])
            ->name('solicitud.reenviarPendientes');

        // NUEVA RUTA: Para reenviar una solicitud individual
        Route::post('solicitud/{solicitud}/reenviar', [SolicitudController::class, 'reenviarSolicitud'])
            ->name('solicitud.reenviar');

            Route::get('solicitud/{solicitud}/calificar', [SolicitudController::class, 'calificar'])
            ->name('solicitud.calificar')
            ->middleware('can:admin.solicitud.edit');

            Route::get('solicitud/{solicitud}/calificar', [SolicitudController::class, 'calificar'])
            ->name('solicitud.calificar')
            ->middleware('can:admin.solicitud.edit');

            Route::post('solicitud/{solicitud}/calificar', [SolicitudController::class, 'storeCalificacion'])
                ->name('solicitud.storeCalificacion')
                ->middleware('can:admin.solicitud.edit');













        Route::resource('roles', RoleController::class);

    });


/*
|--------------------------------------------------------------------------
| Rutas de Archivos
|--------------------------------------------------------------------------
|
| Ruta para servir archivos (por ejemplo, para solicitudes). Se aplica el middleware
| 'auth' y 'verified' y se permite el uso de cualquier nombre de archivo.
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
| sincronizar los usuarios en segundo plano, según la lógica implementada en ApiUserSyncController.
|
*/
Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/sync-users', [ApiUserSyncController::class, 'syncUsers'])
            ->name('sync.users');
    });

/*
|--------------------------------------------------------------------------
| Ruta de Origen (Home)
|--------------------------------------------------------------------------
|
| La ruta base redirige directamente al login. Esto asegura que los usuarios no autenticados
| sean dirigidos al inicio de sesión.
|
*/
Route::get('', function () {
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Ruta para el Dashboard
|--------------------------------------------------------------------------
|
| La vista del dashboard se muestra en '/dashboard'. Está protegida con 'auth' y 'verified'.
|
*/
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación y Configuración (Volt)
|--------------------------------------------------------------------------
|
| Estas rutas están destinadas a configuraciones del usuario (perfil, contraseña, apariencia, etc.)
| y redireccionan la ruta 'settings' a 'settings/profile'.
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
| Esta ruta permite que el usuario cierre sesión mediante una petición POST,
| invocando el método 'destroy' del AuthenticatedSessionController.
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
| Inclusión de Rutas de Autenticación
|--------------------------------------------------------------------------
|
| Este archivo contiene las rutas de autenticación generadas por Laravel Breeze,
| Jetstream u otra solución de autenticación que utilices.
|
*/
require __DIR__.'/auth.php';
