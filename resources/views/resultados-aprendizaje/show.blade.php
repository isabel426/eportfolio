@extends('landed.master')
    @section('content')
    <div class="row m-4">

        <div class="col-sm-4">

            <img src='/landed/images/logo.png' style="height:200px"/>

        </div>
        <div class="col-sm-8">

            <h3><strong>Nombre: </strong>{{ $resultados_aprendizaje->nombre }}</h3>
            <h4><strong>Dominio: </strong>
                <a href="http://github.com/2DAW-CarlosIII/{{ $resultados_aprendizaje->codigo }}">
                    http://github.com/2DAW-CarlosIII/{{ $resultados_aprendizaje->codigo }}
                </a>
            </h4>
            <h4><strong>Codigo: </strong>{{ $resultados_aprendizaje->codigo }}</h4>
            <p><strong>Nombre: </strong>{{ $resultados_aprendizaje->nombre }}</p>



            <a class="btn btn-warning" href="{{ action([App\Http\Controllers\ResultadosAprendizajeController::class, 'getEdit'], ($resultados_aprendizaje->id)) }}">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                Editar calificacion del alumno.
            </a>
            <a class="btn btn-outline-info" href="{{ action([App\Http\Controllers\ResultadosAprendizajeController::class, 'getIndex']) }}">
                Volver al listado
            </a>


        </div>
    </div>
    @endsection
