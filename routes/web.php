<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Pantalla principal";
});

Route::get('/login', function() {
    return "Login usuario";
});

Route::get('/logout', function() {
    return "Logout usuario";
});

Route::prefix('familia-profesional')->group(function() {
    Route::get('/', function() {
        return "Listado familia profesional";
    });

    Route::get('/show/{id}', function($id) {
        return "Vista detalle familia profesional " . $id;
    });

    Route::get('/create', function() {
       return "Añadir familia profesional";
    });

    Route::get('/edit/{id}', function($id) {
       return "Modificar familia profesional " . $id;
    });
});

Route::get('/perfil/{id}', function($id) {
    return "Visualizar el usuario de " . $id;
});
