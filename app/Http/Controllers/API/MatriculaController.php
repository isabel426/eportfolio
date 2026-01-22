<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use Illuminate\Http\Request;
use App\Http\Resources\MatriculaResource;
use App\Http\Resources\UserResource;
use App\Models\ModuloFormativo;
use App\Models\User;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$parent_id = null)
    {
        $query = Matricula::query();
        if ($request) {
            $query->orWhere('id', 'like', '%' . $request->q . '%');
        }

        return MatriculaResource::collection(
            $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $parent_id = null)
    {
        $matricula = $request->all();
        if ($parent_id) {
            $matricula['modulo_formativo_id'] = $parent_id;
        }

        $matricula['modulo_formativo_id'] = $parent_id;

        $matriculas = Matricula::create($matricula);

        return new MatriculaResource($matriculas);
    }

    /**
     * Display the specified resource.º
     */
    public function show($parent_id=null, Matricula $id)
    {
        abort_if($id->modulo_formativo_id != $parent_id, 404, 'Matrícula no encontrada en el módulo formativo especificado.');
        return new MatriculaResource($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $parent_id=null, Matricula $id)
    {
        $matriculaData = json_decode($request->getContent(), true);
        $id->update($matriculaData);

        return new MatriculaResource($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($parent_id=null, Matricula $id)
    {
        try {
            $id->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
