<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AsignacionRevisionResource;
use App\Models\AsignacionRevision;
use Illuminate\Http\Request;

class AsignacionRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $query = AsignacionRevision::query();
            if ($query) {
                $query->orWhere('id', 'like', '%' . $request->q . '%');
            }

            return AsignacionRevisionResource::collection(
            AsignacionRevision::orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $asignacionRevision = json_decode($request->getContent(), true);

        $asignacionRevision = AsignacionRevision::create($asignacionRevision);

        return new AsignacionRevisionResource($asignacionRevision);
    }

    /**
     * Display the specified resource.
     */
    public function show(AsignacionRevision $asignacionRevision)
    {
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
    public function destroy(AsignacionRevision $asignacionRevision)
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
