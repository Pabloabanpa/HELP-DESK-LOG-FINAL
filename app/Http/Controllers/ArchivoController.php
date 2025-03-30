<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ArchivoController extends Controller
{
    public function mostrar($archivo)
    {
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($archivo)) {
            abort(404, 'Archivo no encontrado');
        }
        $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($archivo);
        return response()->download($filePath);
    }

}
