<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionRevision extends Model
{
    protected $table='asignaciones_revision';
    protected $fillable = ['asignado_por_id','contenido','tipo','evidencia_id','revisor_id','estado','fecha_limite'];
}
