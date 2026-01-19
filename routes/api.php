<?php

use App\Http\Controllers\API\AsignacionRevisionController;
use App\Http\Controllers\API\ComentarioController;
use App\Http\Controllers\CiclosFormativosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tqdev\PhpCrudApi\Config\Config;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::apiResource('comentarios', ComentarioController::class);
    Route::apiResource('asignaciones_revision', AsignacionRevisionController::class);

});


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
