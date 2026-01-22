<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriterioEvaluacion extends Model
{
    //
    protected $table = 'criterios_evaluacion';
    protected $fillable = ['id', 'codigo', 'descripcion', 'peso_porcentaje', 'orden'];
    public static $filterColumns = [
        'id',
        'codigo',
        'descripcion',
        'peso_porcentaje',
        'orden',
    ];
}
