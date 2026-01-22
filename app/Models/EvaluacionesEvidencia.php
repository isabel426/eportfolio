<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionesEvidencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluacion_id',
        'evidencia_id',
        'url',
        'descripcion',
        'estado_validacion',
    ];


    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class);
    }


    public function evidencia()
    {
        return $this->belongsTo(Evidencia::class);
    }
}

