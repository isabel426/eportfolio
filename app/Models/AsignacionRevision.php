<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionRevision extends Model
{
    protected $table='asignaciones_revision';
    protected $fillable = ['contenido','tipo'];
}
