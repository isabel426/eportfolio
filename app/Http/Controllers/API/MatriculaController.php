<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use Illuminate\Http\Request;
use App\Http\Resources\MatriculaResource;
use App\Http\Resources\ModuloFormativoResource;
use App\Http\Resources\UserResource;
use App\Models\ModuloFormativo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
    public function store(Request $request, Matricula $parent_id)
    {
        $matricula = $request->all();

        $matricula['modulo_formativo_id'] = $parent_id->id;

        $matriculas = Matricula::create($matricula);

        return new MatriculaResource($matriculas);
    }

    /**
     * Display the specified resource.º
     */
    public function show(ModuloFormativo $parent_id, Matricula $id)
    {
        return new MatriculaResource($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuloFormativo $parent_id, Matricula $id)
    {
        $matriculaData = json_decode($request->getContent(), true);
        $id->update($matriculaData);

        return new MatriculaResource($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModuloFormativo $parent_id, Matricula $id)
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

    public function modulos_matriculados(Request $request)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        $matriculas = Matricula::where("estudiante_id", $user->id)->get();

        return ModuloFormativoResource::collection(
            ModuloFormativo::where('id', $matriculas->pluck('modulo_formativo_id'))
                ->orderBy($request->_sort ?? 'nombre', $request->_order ?? 'asc')
                ->paginate($request->perPage)
        );
    }
}
