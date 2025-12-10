<?php

namespace App\Http\Controllers;

use App\Models\CriterioEvaluacion;
use Illuminate\Http\Request;

class CriteriosEvaluacionController extends Controller
{
    public function getIndex(){
        return view('criterios-evaluacion.index')
            ->with('criterios_evaluacion', CriterioEvaluacion::all());
    }

    public function getShow($id){
        return view('criterios-evaluacion.show')
            ->with('criterios_evaluacion', CriterioEvaluacion::findOrFail($id))
            ->with('id', $id);
    }

    public function getCreate(){
        return view('criterios-evaluacion.create');
    }

    public function getEdit($id){
        return view('criterios-evaluacion.edit')
            ->with('criterios_evaluacion', CriterioEvaluacion::findOrFail($id))
            ->with('id', $id);
    }
     public function postCreate(Request $request)
    {
        $criterioEvaluacion = CriterioEvaluacion::create($request->all());
        return redirect()->action([self::class, 'getShow'], ['id' => $criterioEvaluacion->id]);
    }
    public function putCreate(Request $request, $id)
    {
        $criterioEvaluacion = CriterioEvaluacion::findOrFail($id);
        $criterioEvaluacion->update($request->all());
        return redirect()->action([self::class, 'getShow'], ['id' => $criterioEvaluacion->id]);
    }

}
