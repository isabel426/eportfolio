<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = 'matriculas';
    protected $fillable = [
        'id',
        'estudiante_id',
        'modulo_formativo_id',
    ];
    public static $filterColumns = [
        'id',
        'estudiante_id',
        'modulo_formativo_id',
    ];
}
