<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\User;

class MatriculasController extends Controller
{
    public function getIndex()
    {

        $matriculas = Matricula::all();
        return view('matriculas.index')
            ->with('matriculas', $matriculas);
    }
    public function getShow($id)
    {
        $matricula = Matricula::findOrFail($id);
        return view('matriculas.show')
            ->with('matricula', $matricula);
    }
    public function getCreate()
    {
        return view('matriculas.create', [
            'estudiantes' => User::all(),
            //'modulos' => ModuloFormativo::all(),
        ]);
    }
    public function getEdit($id)
    {
        $matricula = Matricula::findOrFail($id);
        return view(
            'matriculas.edit',
            [
                'matricula' => $matricula,
                'estudiantes' => User::all(),
                //'modulos' => ModuloFormativo::all(),
            ]
        );
    }
    public function postCreate(Request $request)
    {
        $matricula = new Matricula();
        $matricula->fill($request->all());
        $matricula->save();

        return redirect()->action([self::class, 'getShow'], ['id' => $matricula->id]);
    }
    public function putCreate(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);
        $matricula->fill($request->all());
        $matricula->save();

        return redirect()->action([self::class, 'getShow'], ['id' => $matricula->id]);
    }
}

