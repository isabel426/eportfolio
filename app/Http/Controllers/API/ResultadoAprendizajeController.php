<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ResultadoAprendizaje;
use Illuminate\Http\Request;
use App\Http\Resources\ResultadoAprendizajeResource;
use App\Models\ModuloFormativo;

class ResultadoAprendizajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ResultadoAprendizaje::query();
        if($request) {
            $query->orWhere('id', 'like', '%' .$request->q . '%');
        }
        return ResultadoAprendizajeResource::collection(
            $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$parent_id)
    {
        $resultado = $request->all();

        $resultado['modulo_formativo_id'] = $parent_id;
        $resultadoAprendizaje = ResultadoAprendizaje::create($resultado);

        return new ResultadoAprendizajeResource($resultadoAprendizaje);
    }

    /**
     * Display the specified resource.
     */
    public function show($parent_id, ResultadoAprendizaje $id)
    {
        abort_if($id->modulo_formativo_id != $parent_id, 404, 'Resultado de aprendizaje no encontrado en el módulo formativo especificado.');
        return new ResultadoAprendizajeResource($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$parent_id, ResultadoAprendizaje $id)
    {
        $resultadoAprendizajeData = json_decode($request->getContent(), true);
        $id->update($resultadoAprendizajeData);

        return new ResultadoAprendizajeResource($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($parent_id, ResultadoAprendizaje $id)
    {
        try {
            abort_if($id->modulo_formativo_id != $parent_id, 404, 'Resultado de aprendizaje no encontrado en el módulo formativo especificado.');
            $id->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
