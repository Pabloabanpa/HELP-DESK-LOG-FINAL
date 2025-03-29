<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Atencion;
use App\Models\Anotacion;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'solicitante',
        'tecnico',
        'descripcion',
        'archivo',
        'estado',
        'prioridad', 

    ];

    public function solicitanteUser()
    {
        return $this->belongsTo(User::class, 'solicitante');
    }

    public function tecnicoUser()
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
