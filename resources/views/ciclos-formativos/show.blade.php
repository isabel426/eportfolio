@extends('landed.master')
    @section('content')
    <div class="row m-4">

        <div class="col-sm-4">

            <img src='/landed/images/logo.png' style="height:200px"/>

        </div>
        <div class="col-sm-8">

            <h3><strong>Nombre: </strong>{{ $ciclo->nombre }}</h3>
            <h4><strong>Dominio: </strong>
                <a href="http://github.com/2DAW-CarlosIII/{{ $ciclo->codigo }}">
                    http://github.com/2DAW-CarlosIII/{{ $ciclo->codigo }}
                </a>
            </h4>
            <h4><strong>Codigo: </strong>{{ $ciclo->codigo }}</h4>
            <p><strong>Nombre: </strong>{{ $ciclo->nombre }}</p>


            @auth
            <a class="btn btn-warning" href="{{ action([App\Http\Controllers\CiclosFormativosController::class, 'getEdit'], ['id' => $ciclo->id]) }}">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                Modificar ciclo formativo del alumno.
            </a>
            @endauth
            <a class="btn btn-outline-info" href="{{ action([App\Http\Controllers\CiclosFormativosController::class, 'getIndex']) }}">
                Volver al listado
            </a>


        </div>
    </div>
    @endsection
