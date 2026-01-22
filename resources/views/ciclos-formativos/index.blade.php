@extends('landed.master')
    @section('content')
<div class="row">

    @foreach ($ciclos as $ciclo)

    <div class="col-4 col-6-medium col-12-small">
        <section class="box">
            <a href="#" class="image featured"><img src="{{ asset('/landed/images/logo.png') }}" style="height:200px" /></a>
            <header>
                <h3>{{ $ciclo->nombre }}</h3>
            </header>
            <p>
                <a href="http://github.com/2DAW-CarlosIII/{{ $ciclo->codigo }}">
                    http://github.com/2DAW-CarlosIII/{{ $ciclo->codigo }}
                </a>
            </p>
            <footer>
                <ul class="actions">
                    <li><a href="{{ action([App\Http\Controllers\CiclosFormativosController::class, 'getShow'], ($ciclo->id) ) }}" class="button alt">Más info</a></li>
                </ul>
            </footer>
        </section>
    </div>

    @endforeach
</div>

    @endsection
