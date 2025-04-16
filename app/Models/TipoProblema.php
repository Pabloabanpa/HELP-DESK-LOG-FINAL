<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProblema extends Model
{
    use HasFactory;

    protected $table = 'tipo_problemas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'area_solucion'
        ];



public function solicitudes()
{
    return $this->hasMany(\App\Models\Solicitud::class, 'tipo_problema');
}


}
