<?php

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FamiliasProfesionalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'getHome']);

// ----------------------------------------
Route::get('login', function () {
    return view('auth.login');
});
Route::get('logout', function () {
    return "Logout usuario";
});


// ----------------------------------------
Route::prefix('familias_profesionales')->group(function () {

   Route::get('/', [FamiliasProfesionalesController::class, 'getIndex']);


   Route::get('create', [FamiliasProfesionalesController::class, 'getCreate']);


    Route::get('show/{id}',[FamiliasProfesionalesController::class,'getShow']) -> where('id', '[0-9]+');

    Route::get('edit/{id}',[FamiliasProfesionalesController::class,'getEdit']) -> where('id', '[0-9]+');

    Route::post('store',[FamiliasProfesionalesController::class,'store']);

    Route::put('update/{id}',[FamiliasProfesionalesController::class,'update'])->where('id', '[0-9]+');

});


// ----------------------------------------
Route::get('perfil/{id?}', function ($id = null) {
    if ($id === null)
        return 'Visualizar el currículo propio';
    return 'Visualizar el currículo de ' . $id;
}) -> where('id', '[0-9]+');


