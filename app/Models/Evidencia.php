<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    use HasFactory;


    protected $table = 'evidencias';


    protected $fillable = [
        'estudiante_id', 'tarea_id', 'url', 'descripcion', 'estado_validacion', 'criterio_evaluacion_id'
    ];


    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }


    public function criterioEvaluacion()
    {
        return $this->belongsTo(CriterioEvaluacion::class);
    }
}
