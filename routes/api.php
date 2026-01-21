<?php

use App\Http\Controllers\API\CicloController;
use App\Http\Controllers\API\FamiliaProfesionalController;
use App\Http\Controllers\API\ModuloFormativoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config\Config;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// Rutas /api/v1

Route::prefix('v1')->group(function () {

    Route::get('modulos-impartidos', [ModuloFormativoController::class, 'index'])->middleware('auth:sanctum');;

    Route::apiResource('familias-profesionales', FamiliaProfesionalController::class)
        ->parameters([
            'familias-profesionales' => 'id'
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
});


// Rutas PHP-CRUD-API
Route::any('/{any}', function (ServerRequestInterface $request) {
    $config = new Config([
        'address' => env('DB_HOST', '127.0.0.1'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
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
