<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EvidenciaResource;
use App\Models\Evidencia;
use App\Models\Evidencias;
use App\Models\Tarea;
use Illuminate\Http\Request;

class EvidenciasController extends Controller
{
    public function index(Tarea $tarea, Request $request)
    {
        return EvidenciaResource::collection(
            $tarea->evidencias()
            ->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->perPage)
        );
    }

    public function store(Request $request, Tarea $tarea)
    {
        $data = $request->all();
        $data['tarea_id'] = $tarea->id;

        $evidencia = Evidencia::create($data);

        return new EvidenciaResource($evidencia);
    }
    public function show(Tarea $tarea, Evidencia $evidencia)
    {
        return new EvidenciaResource($evidencia);
    }

    public function update(Request $request, Tarea $tarea, Evidencia $evidencia)
{
    $evidenciaData = json_decode($request->getContent(), true);
    $evidencia->update($evidenciaData);

    return new EvidenciaResource($evidencia);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarea $tarea, Evidencia $evidencia)
    {
        try {
            $evidencia->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
