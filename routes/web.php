<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CriteriosEvaluacionController;
use App\Http\Controllers\CiclosFormativosController;
use App\Http\Controllers\EvidenciasController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FamiliasProfesionalesController;
use App\Http\Controllers\ResultadosAprendizajeController;
use App\Http\Controllers\MatriculasController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', [WelcomeController::class, 'getHome'])->name('home');

// ----------------------------------------
Route::get('login', function () {
    return view('auth.login');
});
Route::get('logout', function () {
    return "Logout usuario";
});


// ----------------------------------------
Route::prefix('familias-profesionales')->group(function () {

    Route::get('/', [FamiliasProfesionalesController::class, 'getIndex']);

    Route::get('show/{id}', [FamiliasProfesionalesController::class, 'getShow'])->where('id', '[0-9]+');


    Route::group(['middleware' => 'auth'], function () {

        Route::get('create', [FamiliasProfesionalesController::class, 'getCreate']);
        Route::get('edit/{id}', [FamiliasProfesionalesController::class, 'getEdit'])->where('id', '[0-9]+');
        Route::post('store', [FamiliasProfesionalesController::class, 'postCreate']);
        Route::put('update/{id}', [FamiliasProfesionalesController::class, 'putCreate'])->where('id', '[0-9]+');
    });
});


Route::prefix('resultados-aprendizaje')->group(function () {

    Route::get('/', [ResultadosAprendizajeController::class, 'getIndex']);
    Route::get('show/{id}', [ResultadosAprendizajeController::class, 'getShow'])->where('id', '[0-9]+');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('create', [ResultadosAprendizajeController::class, 'getCreate']);
        Route::get('edit/{id}', [ResultadosAprendizajeController::class, 'getEdit'])->where('id', '[0-9]+');
        Route::post('store', [ResultadosAprendizajeController::class, 'postCreate']);
        Route::put('update/{id}', [ResultadosAprendizajeController::class, 'putCreate'])->where('id', '[0-9]+');
    });
});


Route::prefix('ciclos-formativos')->group(function () {

    Route::get('/', [CiclosFormativosController::class, 'getIndex']);
    Route::get('show/{id}', [CiclosFormativosController::class, 'getShow'])->where('id', '[0-9]+');


    Route::group(['middleware' => 'auth'], function () {
        Route::get('create', [CiclosFormativosController::class, 'getCreate']);
        Route::get('edit/{id}', [CiclosFormativosController::class, 'getEdit'])->where('id', '[0-9]+');
        Route::post('store', [CiclosFormativosController::class, 'postCreate']);
        Route::put('update/{id}', [CiclosFormativosController::class, 'putCreate'])->where('id', '[0-9]+');
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
    Route::get('show/{id}', [CriteriosEvaluacionController::class, 'getShow'])->where('id', '[0-9]+');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('create', [CriteriosEvaluacionController::class, 'getCreate']);
        Route::get('edit/{id}', [CriteriosEvaluacionController::class, 'getEdit'])->where('id', '[0-9]+');
        Route::post('store', [CriteriosEvaluacionController::class, 'postCreate']);
        Route::put('update/{id}', [CriteriosEvaluacionController::class, 'putCreate'])->where('id', '[0-9]+');
    });
});

//----------------------------------------------------------------
Route::prefix('evidencias')->group(function () {

    Route::get('/', [EvidenciasController::class, 'getIndex']);

    Route::get('show/{id}', [EvidenciasController::class, 'getShow'])->where('id', '[0-9]+');


    Route::group(['middleware' => 'auth'], function () {

        Route::get('create', [EvidenciasController::class, 'getCreate']);
        Route::get('edit/{id}', [EvidenciasController::class, 'getEdit'])->where('id', '[0-9]+');
        Route::post('store', [EvidenciasController::class, 'postCreate']);
        Route::put('update/{id}', [EvidenciasController::class, 'putCreate'])->where('id', '[0-9]+');
    });
});
Route::prefix('matriculas')->group(function () {
        Route::get('/', [MatriculasController::class, 'getIndex']);
        Route::get('show/{id}', [MatriculasController::class, 'getShow'])->where('id', '[0-9]+');

        Route::group(['middleware' => 'auth'], function () {
            Route::get('create', [MatriculasController::class, 'getCreate']);
            Route::get('edit/{id}', [MatriculasController::class, 'getEdit'])->where('id', '[0-9]+');
            Route::post('store', [MatriculasController::class, 'postCreate']);
            Route::put('update/{id}', [MatriculasController::class, 'putCreate'])->where('id', '[0-9]+');
        });
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
