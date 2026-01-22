<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EvaluacionesEvidenciasResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'evaluacion_id' => $this->evaluacion_id,
            'evidencia_id' => $this->evidencia_id,
            'url' => $this->url,
            'descripcion' => $this->descripcion,
            'estado_validacion' => $this->estado_validacion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
