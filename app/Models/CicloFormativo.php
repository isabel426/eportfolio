<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicloFormativo extends Model
{
    protected $table = 'ciclos_formativos';

    protected $fillable = [
        'familia_profesional_id',
        'nombre',
        'codigo',
        'grado',
        'descripcion'
    ];

    public static $filterColumns = [
        'familia_profesional_id',
        'nombre',
        'codigo',
        'grado',
        'descripcion'
    ];

}
