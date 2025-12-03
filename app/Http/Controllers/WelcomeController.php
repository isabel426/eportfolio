<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function getHome(){
        return redirect()->action([FamiliasProfesionalesController::class,'getIndex']);
    }
}
