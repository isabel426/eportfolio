<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'criterio_evaluacion_id',
        'fecha_apertura',
        'fecha_cierre',
        'activo',
        'enunciado'
    ];

    public function criterioEvaluacion()
    {
        return $this->belongsTo(CriterioEvaluacion::class);
    }


    public function evidencias()
    {
        return $this->hasMany(Evidencia::class);
    }
}
