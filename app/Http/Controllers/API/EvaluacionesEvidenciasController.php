<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EvaluacionesEvidenciasResource;
use App\Models\EvaluacionesEvidencia;
use App\Models\Evidencia;
use Illuminate\Http\Request;

class EvaluacionesEvidenciasController extends Controller
{
    public function index(Request $request)
    {
        return EvaluacionesEvidenciasResource::collection(
            EvaluacionesEvidencia::orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->perPage));
    }

    public function store(Request $request)
    {
        $evaluacionEvidencia = json_decode($request->getContent(), true);

        $evaluacionEvidencia = EvaluacionesEvidencia::create($evaluacionEvidencia);

        return new EvaluacionesEvidenciasResource($evaluacionEvidencia);
    }

    public function show(Evidencia $evidencia, EvaluacionesEvidencia $evaluacionEvidencia)
    {
        
        return new EvaluacionesEvidenciasResource($evaluacionEvidencia);
    }

     public function update(Request $request, EvaluacionesEvidencia $evaluacionEvidencia)
    {
        $evaluacionEvidenciaData = json_decode($request->getContent(), true);
        $evaluacionEvidencia->update($evaluacionEvidenciaData);

        return new EvaluacionesEvidenciasResource($evaluacionEvidencia);
    }

    public function destroy(EvaluacionesEvidencia $evaluacionEvidencia)
    {
        try {
            $evaluacionEvidencia->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}

