<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitud_id',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Cada atención pertenece a una solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }
}
