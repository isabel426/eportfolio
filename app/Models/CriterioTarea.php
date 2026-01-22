<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriterioTarea extends Model
{
    protected $table='criterios_tareas';
    protected $fillable = ['tarea_id','actividad_id'];
}
