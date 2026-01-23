<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsignacionRevisionResource;
use App\Models\AsignacionRevision;
use App\Models\Evidencia;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Evidencia $evidencia)
    {
         $query = AsignacionRevision::where('evidencia_id',$evidencia->id);
            if ($query) {
                $query->orWhere('revisor_id', 'like', '%' . $request->q . '%');
            }

            return AsignacionRevisionResource::collection(
            AsignacionRevision::where('evidencia_id',$evidencia->id)
            ->orderBy($request->sort ?? 'id', $request->order ?? 'asc')
            ->paginate($request->per_page));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Evidencia $evidencia,AsignacionRevision $asignacionRevision)
    {
        $asignacionRevisionData = json_decode($request->getContent(), true);
        $asignacionRevision = AsignacionRevision::create($asignacionRevisionData);

        return new AsignacionRevisionResource($asignacionRevision);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evidencia $evidencia,AsignacionRevision $asignacionRevision)
    {
         return new AsignacionRevisionResource($asignacionRevision);

    }

    public function getShow(Request $request, AsignacionRevision $asignacionRevision,  $id)
    {

        $query = AsignacionRevision::where('revisor_id', $id);
        if($request->estado_asignacion){
            $asignaciones = $query->where('estado', $request->estado_asignacion)
                ->orderBy($request->sort ?? 'id', $request->order ?? 'asc')
                ->paginate($request->per_page);

        }else{
             $asignaciones = $query
            ->orderBy($request->sort ?? 'id', $request->order ?? 'asc')
            ->paginate($request->per_page);

        }

        return AsignacionRevisionResource::collection($asignaciones);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evidencia $evidencia, AsignacionRevision $asignacionRevision)
    {
        $asignacionData = json_decode($request->getContent(), true);
        $asignacionRevision->update($asignacionData);
 
        return new AsignacionRevisionResource($asignacionRevision);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evidencia $evidencia,AsignacionRevision $asignacionRevision)
    {
        try {
            $asignacionRevision->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
