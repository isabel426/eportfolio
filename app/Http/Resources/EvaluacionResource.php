<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EvaluacionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'criterio_evaluacion_id' => $this->criterio_evaluacion_id,
            'puntuacion' => $this->puntuacion,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
