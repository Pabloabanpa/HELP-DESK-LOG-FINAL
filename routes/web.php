<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\AtencionController;
use App\Http\Controllers\AnotacionController;
use App\Http\Controllers\UserController;
use App\Models\Solicitud;

//RUTAS DE ADMINISTRADOR
Route::get('/admin/solicitudes', [SolicitudController::class, 'index'])->name('admin.solicitudes');
Route::get('/admin/solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('admin.solicitudes.show');
Route::get('/admin/solicitudes/{solicitud}/edit', [SolicitudController::class, 'edit'])->name('admin.solicitudes.edit');
Route::put('/admin/solicitudes/{solicitud}', [SolicitudController::class, 'update'])->name('admin.solicitudes.update');
Route::delete('/admin/solicitudes/{solicitud}', [SolicitudController::class, 'destroy'])->name('admin.solicitudes.destroy');
Route::get('/admin/solicitudes/create', [SolicitudController::class, 'create'])->name('admin.solicitudes.create');

Route::get('/admin/usuarios', function () {
    $users = \App\Models\User::all();
    return view('admin.user.index', compact('users'));
})->middleware(['auth', 'verified'])->name('admin.usuarios');

//Route::resource('user', UserController::class)->name('admin.users');










//FIN DE LAS RUA DE ADMINISTRADOR



Route::get('', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



//Route::get('/solicitudes', function () {
   // $solicitudes = Solicitud::with('solicitanteUser', 'tecnicoUser')->get();
   // return view('solicitudes.index', compact('solicitudes'));
//})->middleware(['auth', 'verified'])->name('solicitudes.index');




//RUTAS DE AUTH
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
//FIN DE LAS RUTAS DE AUTH
