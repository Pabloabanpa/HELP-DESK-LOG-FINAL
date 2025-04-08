<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * En este caso se incluyen el usuario (solicitante), descripción y estado.
     *
     * @var array
     */
    protected $fillable = [
        'solicitante',
        'descripcion',
        'estado',
    ];

    /**
     * Relación: Un préstamo pertenece a un usuario (solicitante).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function solicitanteUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'solicitante');
    }
}
