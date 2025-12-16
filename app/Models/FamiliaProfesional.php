<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamiliaProfesional extends Model
{
        protected $table = 'familias_profesionales';

        protected $fillable = ['codigo', 'nombre', 'imagen'];

        public static $filterColumns = [
        'codigo',
        'nombre',
        'imagen'
    ];
}
