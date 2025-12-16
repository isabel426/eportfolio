<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CriteriosEvaluacionController;
use App\Http\Controllers\CiclosFormativosController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FamiliasProfesionalesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultadosAprendizajeController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/', [WelcomeController::class, 'getHome'])
    ->name('home');

// ----------------------------------------
Route::prefix('familias-profesionales')->group(function () {

    Route::get('/', [FamiliasProfesionalesController::class, 'getIndex']);


    Route::get('create', [FamiliasProfesionalesController::class, 'getCreate'])
    ->middleware('auth');


    Route::get('show/{id}', [FamiliasProfesionalesController::class, 'getShow'])->where('id', '[0-9]+');

    Route::get('edit/{id}', [FamiliasProfesionalesController::class, 'getEdit'])->where('id', '[0-9]+')
    ->middleware('auth');

    Route::post('store', [FamiliasProfesionalesController::class, 'postCreate'])
    ->middleware('auth');

    Route::put('update/{id}', [FamiliasProfesionalesController::class, 'putCreate'])->where('id', '[0-9]+')
    ->middleware('auth');

    Route::group(['middleware' => 'auth'], function () {

        Route::get('create', [FamiliasProfesionalesController::class, 'getCreate']);
        Route::get('edit/{id}', [FamiliasProfesionalesController::class, 'getEdit'])->where('id', '[0-9]+');
        Route::post('store', [FamiliasProfesionalesController::class, 'postCreate']);
        Route::put('update/{id}', [FamiliasProfesionalesController::class, 'putCreate'])->where('id', '[0-9]+');
    });
});

            Route::get('create', [ResultadosAprendizajeController::class, 'getCreate'])
            ->middleware('auth');

Route::prefix('resultados-aprendizaje')->group(function () {

            Route::get('edit/{id}',[ResultadosAprendizajeController::class,'getEdit']) -> where('id', '[0-9]+')
            ->middleware('auth');

            Route::post('store',[ResultadosAprendizajeController::class,'postCreate'])
            ->middleware('auth');

            Route::put('update/{id}',[ResultadosAprendizajeController::class,'putCreate'])->where('id', '[0-9]+')
            ->middleware('auth');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('create', [ResultadosAprendizajeController::class, 'getCreate']);
        Route::get('edit/{id}', [ResultadosAprendizajeController::class, 'getEdit'])->where('id', '[0-9]+');
        Route::post('store', [ResultadosAprendizajeController::class, 'postCreate']);
        Route::put('update/{id}', [ResultadosAprendizajeController::class, 'putCreate'])->where('id', '[0-9]+');
    });
});


        Route::get('create', [CiclosFormativosController::class, 'getCreate'])
        ->middleware('auth');

    Route::get('/', [CiclosFormativosController::class, 'getIndex']);
    Route::get('show/{id}', [CiclosFormativosController::class, 'getShow'])->where('id', '[0-9]+');


        Route::get('edit/{id}', [CiclosFormativosController::class, 'getEdit'])->where('id', '[0-9]+')
        ->middleware('auth');

        Route::post('store', [CiclosFormativosController::class, 'postCreate'])
        ->middleware('auth');

        Route::put('update/{id}', [CiclosFormativosController::class, 'putCreate'])->where('id', '[0-9]+')
        ->middleware('auth');
    });
});



// ----------------------------------------
Route::get('perfil/{id?}', function ($id = null) {
    if ($id === null)
        return 'Visualizar el currículo propio';
    return 'Visualizar el currículo de ' . $id;
})->where('id', '[0-9]+');


Route::prefix('criterios-evaluacion')->group(function () {

    Route::get('/', [CriteriosEvaluacionController::class, 'getIndex']);
    Route::get('create', [CriteriosEvaluacionController::class, 'getCreate'])
    ->middleware('auth');
    Route::get('show/{id}', [CriteriosEvaluacionController::class, 'getShow'])->where('id', '[0-9]+');
    Route::get('edit/{id}', [CriteriosEvaluacionController::class, 'getEdit'])->where('id', '[0-9]+')
    ->middleware('auth');
    Route::post('store', [CriteriosEvaluacionController::class, 'postCreate'])
    ->middleware('auth');
    Route::put('update/{id}', [CriteriosEvaluacionController::class, 'putCreate'])->where('id', '[0-9]+')
    ->middleware('auth');
});

require __DIR__.'/auth.php';
