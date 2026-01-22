<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CriterioEvaluacion;

class ResultadoAprendizaje extends Model
{
    protected $table = 'resultados_aprendizaje';

    protected $fillable = ['id', 'codigo', 'descripción', 'orden'];

    public static $filterColumns = [
        'id',
        'codigo',
        'descripción',
        'orden',
    ];
}
