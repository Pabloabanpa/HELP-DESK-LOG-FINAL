<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Solicitud;

class Atencion extends Model
{
    use HasFactory;
    protected $table = 'atenciones';

    protected $fillable = [
        'solicitud_id',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Relación: una atención pertenece a una solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

}
