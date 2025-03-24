<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anotacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitud_id',
        'tecnico_id',
        'descripcion',
        'material_usado',
    ];

    // Cada anotación pertenece a una solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    // Relación con el técnico que hizo la anotación
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
