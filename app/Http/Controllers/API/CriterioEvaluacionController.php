<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CriterioEvaluacion;
use Illuminate\Http\Request;
use App\Http\Resources\CriterioEvaluacionResource;
use App\Models\ResultadoAprendizaje;

class CriterioEvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = CriterioEvaluacion::query();
        if($request) {
            $query->orWhere('id', 'like', '%' .$request->q . '%');
        }
        return CriterioEvaluacionResource::collection(
            $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $parent_id)
    {
        $criterio = $request->all();

        $criterio['resultado_aprendizaje_id'] = $parent_id;

        $criterioEvaluacion = CriterioEvaluacion::create($criterio);

        return new CriterioEvaluacionResource($criterioEvaluacion);
    }

    /**
     * Display the specified resource.
     */
    public function show($parent_id,ResultadoAprendizaje $resultadoAprendizaje, CriterioEvaluacion $id)
    {
        abort_if($id->resultado_aprendizaje_id != $resultadoAprendizaje->id, 404, 'Criterio de evaluación no encontrado en el resultado de aprendizaje especificado.');
        return new CriterioEvaluacionResource($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $parent_id=null, CriterioEvaluacion $id)
    {
        $criterioData = json_decode($request->getContent(), true);
        $id->update($criterioData);

        return new CriterioEvaluacionResource($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($parent_id=null, CriterioEvaluacion $id)
    {
        try {
            abort_if($id->resultado_aprendizaje_id != $parent_id, 404, 'Criterio de evaluación no encontrado en el resultado de aprendizaje especificado.');
            $id->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
