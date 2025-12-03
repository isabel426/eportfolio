<?php

namespace App\Http\Controllers;

use App\Models\ResultadoAprendizaje;
use Illuminate\Http\Request;

class ResultadosAprendizajeController extends Controller
{
    public function getIndex(){
        return view('resultados-aprendizaje.index')
            ->with('resultados_aprendizaje', ResultadoAprendizaje::all());
    }

    public function getShow($id){
        return view('resultados-aprendizaje.show')
            ->with('resultados_aprendizaje', ResultadoAprendizaje::findOrFail($id))
            ->with('id', $id);
    }

    public function getCreate(){
        return view('resultados_aprendizaje.create');
    }

    public function getEdit($id){
        return view('resultados_aprendizaje.edit')
            ->with('resultados-aprendizaje', ResultadoAprendizaje::findOrFail($id))
            ->with('id', $id);
    }


}
