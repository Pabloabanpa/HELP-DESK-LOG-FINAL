<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Cierra la sesión del usuario actual y lo redirige al login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        // Redirige a la ruta de login
        return redirect()->route('login');
    }
}
