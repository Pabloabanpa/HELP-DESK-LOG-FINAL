<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anotacion extends Model
{
    use HasFactory;

    // Especificar la tabla en la base de datos, si es necesario.
    protected $table = 'anotaciones';

    // Campos asignables
    protected $fillable = [
        'atencion_id',
        'tecnico_id',
        'descripcion',
        'material_usado',
    ];

    /**
     * Relación: Una anotación pertenece a una atención.
     */
    public function atencion()
    {
        return $this->belongsTo(Atencion::class);
    }

    /**
     * Relación: Una anotación pertenece a un técnico (usuario).
     */
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
