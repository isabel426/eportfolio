<?php

use App\Http\Controllers\API\AsignacionRevisionController;
use App\Http\Controllers\API\CicloController;
use App\Http\Controllers\API\ComentarioController;
use App\Http\Controllers\API\CriterioEvaluacionController;
use App\Http\Controllers\API\CriterioTareaController;
use App\Http\Controllers\API\FamiliaProfesionalController;
use App\Http\Controllers\API\ModuloFormativoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tqdev\PhpCrudApi\Config\Config;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;
use App\Http\Controllers\API\EvidenciasController;
use App\Http\Controllers\API\TareaController;
use App\Http\Controllers\API\EvaluacionController;
use App\Http\Controllers\API\EvaluacionesEvidenciasController;
use App\Http\Controllers\API\MatriculaController;
use App\Http\Controllers\API\ResultadoAprendizajeController;
use App\Http\Controllers\API\RolController;
use App\Http\Controllers\CriteriosEvaluacionController;


Route::middleware(['auth:sanctum'])->get('/user',function (Request $request) {
    return $request->user();
});


// Rutas /api/v1

Route::prefix('v1')->group(function () {

    Route::apiResource('modulos-impartidos', ModuloFormativoController::class);
    
    Route::apiResource('familias-profesionales', FamiliaProfesionalController::class)
        ->parameters([
            'familias-profesionales' => 'familiaProfesional'
        ]);

    Route::apiResource('familias-profesionales.ciclos-formativos', CicloController::class)
        ->parameters([
            'familias-profesionales' => 'parent_id',
            'ciclos-formativos' => 'id'
        ]);

    Route::apiResource('ciclos-formativos.modulos-formativos', ModuloFormativoController::class)
        ->parameters([
            'ciclos-formativos' => 'parent_id',
            'modulos-formativos' => 'id'
        ]);

    Route::apiResource('evidencias.comentarios', ComentarioController::class)->parameters([
        'evidencias' => 'evidencia',
        'comentarios' => 'comentario'
    ]);

    Route::apiResource('evidencias.asignaciones-revision', AsignacionRevisionController::class)->parameters([
        'evidencias' => 'evidencia',
        'asignaciones-revision' => 'asignacionRevision'
    ]);

    Route::get('users/{id}/asignaciones-revision', [AsignacionRevisionController::class,'getShow']);

    Route::apiResource('criterios_tareas', CriterioTareaController::class)->parameters([
        'criterios_tareas' => 'criterioTarea'
    ]);

    Route::apiResource('tareas', TareaController::class)->parameters([
        'tareas' => 'tarea'
    ]);

    Route::apiResource('tareas.evidencias', EvidenciasController::class)
    ->parameters([
        'tareas' => 'tarea',
        'evidencias' => 'evidencia'
    ]);

    Route::apiResource('criterios-evaluacion.tareas', TareaController::class)->parameters([
        'criterios-evaluacion' => 'criterioEvaluacion',
        'tareas' => 'tarea'
    ]);

    Route::apiResource('evidencias.evaluaciones-evidencias', EvaluacionesEvidenciasController::class)->parameters([
        'evaluaciones-evidencias' => 'evaluacionEvidencia'
    ]);



    Route::apiResource('matriculas', MatriculaController::class);

    Route::apiResource('modulos-formativos.resultados-aprendizaje',ResultadoAprendizajeController::class)
        ->parameters(['modulos-formativos' => 'parent_id', 'resultados-aprendizaje' => 'id'
    ]);

    Route::apiResource('resultados-aprendizaje.criterios-evaluacion', CriterioEvaluacionController::class)
        ->parameters([
         'resultados-aprendizaje' => 'parent_id',
         'criterios-evaluacion' => 'id'
    ]);

    Route::apiResource('modulos-formativos.matriculas', MatriculaController::class)
        ->parameters(['modulos-formativos' => 'parent_id', 'matriculas' => 'id'
    ]);

    Route::apiResource('roles', RolController::class)->parameters([
        'roles' => 'rol'
    ]);

});


// Rutas PHP-CRUD-API



Route::any('/{any}', function (ServerRequestInterface $request) {
    $config = new Config([
        'address' => env('DB_HOST', 'mariadb'),
        'database' => env('DB_DATABASE', 'eportfolio'),
        'username' => env('DB_USERNAME', 'eportfolio'),
        'password' => env('DB_PASSWORD', 'eportfolio'),
        'basePath' => '/api',
    ]);
    $api = new Api($config);
    $response = $api->handle($request);

    try {
        $records = json_decode($response->getBody()->getContents())->records;
        $response = response()->json($records, 200, $headers = ['X-Total-Count' => count($records)]);
    } catch (\Throwable $th) {

    }
    return $response;

})->where('any', '.*');












