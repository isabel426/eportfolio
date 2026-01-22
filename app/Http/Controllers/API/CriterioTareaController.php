<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CriterioTareaResource;
use App\Models\CriterioTarea;
use Illuminate\Http\Request;

class CriterioTareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
            $query = CriterioTarea::query();
            if ($query) {
                $query->orWhere('id', 'like', '%' . $request->q . '%');
            }

            return CriterioTareaResource::collection(
            CriterioTarea::orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $criterioTarea = json_decode($request->getContent(), true);

        $criterioTarea = CriterioTarea::create($criterioTarea);

        return new CriterioTareaResource($criterioTarea);
    }

    /**
     * Display the specified resource.
     */
    public function show(CriterioTarea $criterioTarea)
    {
         return new CriterioTareaResource($criterioTarea);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CriterioTarea $criterioTarea)
    {
        $criterioData = json_decode($request->getContent(), true);
        $criterioTarea->update($criterioData);

        return new CriterioTareaResource($criterioTarea);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CriterioTarea $criterioTarea)
    {
         try {
            $criterioTarea->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
    
}
