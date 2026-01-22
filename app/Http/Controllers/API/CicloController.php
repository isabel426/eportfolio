<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CicloResource;
use App\Models\CicloFormativo;
use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;

class CicloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CicloFormativo::query();
        if ($query) {
            $query->orWhere('nombre', 'like', '%' . $request->q . '%');
        }

        return CicloResource::collection(
            CicloFormativo::orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
                ->paginate($request->perPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $parent_id)
    {
        $cicloData = json_decode($request->getContent(), true);

        $cicloData['familia_profesional_id'] = $parent_id;

        $ciclo = CicloFormativo::create($cicloData);

        return new CicloResource($ciclo);
    }

    /**
     * Display the specified resource.
     */
    public function show($parent_id, FamiliaProfesional $familiaProfesional, CicloFormativo $id)
    {
        return new CicloResource($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $parent_id, CicloFormativo $id)
    {
        $cicloData = json_decode($request->getContent(), true);
        $id->update($cicloData);
        return new CicloResource($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($parent_id, CicloFormativo $id)
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
