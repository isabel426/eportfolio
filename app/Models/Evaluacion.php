<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'evidencia_id',
        'user_id',
        'puntuacion',
        'estado',
        'observaciones',
    ];

    public function evidencia()
    {
        return $this->belongsTo(Evidencia::class);
    }


    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
