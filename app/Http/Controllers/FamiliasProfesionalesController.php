<?php

namespace App\Http\Controllers;

use App\Models\FamiliaProfesional;
use Illuminate\Http\Request;

class FamiliasProfesionalesController extends Controller
{
    public function getIndex()
    {
        $familias_profesionales = FamiliaProfesional::all();
        return view('familias-profesionales.index')
            ->with('familias_profesionales', $familias_profesionales);
    }

    public function getShow($id)
    {
        $familia_profesional = FamiliaProfesional::findOrFail($id);
        return view('familias-profesionales.show')
            ->with('familia_profesional', $familia_profesional);
    }

    public function getCreate()
    {
        return view('familias-profesionales.create');
    }

    public function getEdit($id)
    {
        $familia_profesional = FamiliaProfesional::findOrFail($id);
        return view('familias-profesionales.edit')
            ->with('familia_profesional', $familia_profesional);
    }
}
