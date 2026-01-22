<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuloFormativo extends Model
{
    protected $table = 'modulos_formativos';

    protected $fillable = [
        'id',
        'ciclo_formativo_id',
        'nombre',
        'codigo',
        'horas_totales',
        'curso_escolar',
        'centro',
        'docente_id',
        'descripcion'
    ];

    public static $filterColumns = [
        'id',
        'ciclo_formativo_id',
        'nombre',
        'codigo',
        'horas_totales',
        'curso_escolar',
        'centro',
        'docente_id',
        'descripcion'
    ];
}
