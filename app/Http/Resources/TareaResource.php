<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TareaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'criterio_evaluacion_id' => $this->criterio_evaluacion_id,
            'fecha_apertura' => $this->fecha_apertura,
            'fecha_cierre' => $this->fecha_cierre,
            'activo' => $this->activo,
            'enunciado' => $this->enunciado,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

