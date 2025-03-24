<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\AtencionController;
use App\Http\Controllers\AnotacionController;
use App\Http\Controllers\UserController;
use App\Models\Solicitud;



Route::get('', function () {
    return view('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/admin/usuarios', function () {
    $users = \App\Models\User::all();
    return view('admin.index', compact('users'));
})->middleware(['auth', 'verified'])->name('admin.usuarios');

//Route::get('/solicitudes', function () {
   // $solicitudes = Solicitud::with('solicitanteUser', 'tecnicoUser')->get();
   // return view('solicitudes.index', compact('solicitudes'));
//})->middleware(['auth', 'verified'])->name('solicitudes.index');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
