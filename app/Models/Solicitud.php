<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\atenciones;
use App\Models\anotaciones;

class Solicitud extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitante',
        'tecnico',
        'descripcion',
        'archivo',
        'estado',
    ];

    // Relación con el usuario que solicitó
    public function solicitanteUsuario()
    {
        return $this->belongsTo(User::class, 'solicitante');
    }

    // Relación con el técnico asignado
    public function tecnicoUsuario()
    {
        return $this->belongsTo(User::class, 'tecnico');
    }

    // Una solicitud puede tener muchas atenciones
    public function atenciones()
    {
        return $this->hasMany(Atencion::class);
    }

    // Una solicitud puede tener muchas anotaciones
    public function anotaciones()
    {
        return $this->hasMany(Anotacion::class);
    }
}
