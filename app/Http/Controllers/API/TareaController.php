<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TareaResource;
use App\Models\CriterioEvaluacion;
use App\Models\ResultadoAprendizaje;
use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{

    public function index(Request $request, CriterioEvaluacion $criterioId)
    {
        return TareaResource::collection(
            Tarea::where('criterio_evaluacion_id', $criterioId->id)
                ->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage)
        );
    }

    public function store(Request $request)
    {
        $tareaData = json_decode($request->getContent(), true);

        $tarea = Tarea::create($tareaData);

        return new TareaResource($tarea);
    }

    public function show(CriterioEvaluacion $criterioEvaluacion, Tarea $tarea)
    {
        $tarea = Tarea::where('criterio_evaluacion_id', $tarea)
            ->where('id', $tarea->id)
            ->firstOrFail();

        return new TareaResource($tarea);
    }

    public function update(Request $request, CriterioEvaluacion $criterioEvaluacion, Tarea $tarea)
    {
        $tareaData = json_decode($request->getContent(), true);
        $tarea->update($tareaData);

        return new TareaResource($tarea);
    }

    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        try {
            $tarea->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function tareasPorRA(Request $request, ResultadoAprendizaje $resultadoAprendizaje) {
        $criteriosIds = CriterioEvaluacion::where('resultado_aprendizaje_id', $resultadoAprendizaje->id)->pluck("id")->toArray();

        return TareaResource::collection(
            Tarea::whereIn('criterio_evaluacion_id', $criteriosIds)
                ->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage)
        );
    }

}
