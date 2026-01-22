<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsignacionRevisionResource;
use App\Models\AsignacionRevision;
use App\Models\Evidencias;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Evidencias $evidencia)
    {
         $query = AsignacionRevision::query();
            if ($query) {
                $query->orWhere('id', 'like', '%' . $request->q . '%');
            }

            return AsignacionRevisionResource::collection(
            AsignacionRevision::where('evidencia_id',$evidencia->evidencia_id)
            ->orderBy($request->sort ?? 'id', $request->order ?? 'asc')
            ->paginate($request->per_page));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Evidencias $evidencia,AsignacionRevision $asignacionRevision)
    {
        $asignacionRevisionData = json_decode($request->getContent(), true);
        $asignacionRevision = AsignacionRevision::create($asignacionRevisionData);

        return new AsignacionRevisionResource($asignacionRevision);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evidencias $evidencia,AsignacionRevision $asignacionRevision)
    {
         return new AsignacionRevisionResource($asignacionRevision);

    }

    public function getShow(AsignacionRevision $asignacionRevision,User $usuario)
    {
        $asignacionRevision = AsignacionRevision::where('revisor_id',$usuario->id)->get();
         return new AsignacionRevisionResource($asignacionRevision);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AsignacionRevision $asignacionRevision)
    {
        $asignacionData = json_decode($request->getContent(), true);
        $asignacionRevision->update($asignacionData);

        return new AsignacionRevisionResource($asignacionRevision);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evidencias $evidencia,AsignacionRevision $asignacionRevision)
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
