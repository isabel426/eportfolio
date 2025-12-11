@extends('landed.master')
    @section('content')
    <div class="row m-4">

        <div class="col-sm-4">

            <img src='/landed/images/logo.png' style="height:200px"/>

        </div>
        <div class="col-sm-8">

            <h3><strong>Criterio de Evaluacion ID: </strong>{{ $criterios_evaluacion->resultado_aprendizaje_id}}</h3>
            <h4><strong>Codigo: </strong>
                <a href="http://github.com/2DAW-CarlosIII/{{ $criterios_evaluacion->codigo}}">
                    http://github.com/2DAW-CarlosIII/{{ $criterios_evaluacion->codigo }}
                </a>
            </h4>
            <h4><strong>Codigo: </strong>{{ $criterios_evaluacion->codigo}}</h4>
            <p><strong>Descripcion: </strong>{{ $criterios_evaluacion->descripcion }}</p>
            <p><strong>Peso Porcentaje: </strong>{{ $criterios_evaluacion->peso_porcentaje }}</p>
            <p><strong>Orden: </strong>{{ $criterios_evaluacion->orden }}</p>

            @auth
            <a class="btn btn-warning" href="{{ action([App\Http\Controllers\CriteriosEvaluacionController::class, 'getEdit'], ['id' => $id]) }}">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                Editar criterio de evaluacion del alumno.
            </a>
            @endauth
            <a class="btn btn-outline-info" href="{{ action([App\Http\Controllers\CriteriosEvaluacionController::class, 'getIndex']) }}">
                Volver al listado
            </a>


        </div>
    </div>
    @endsection
