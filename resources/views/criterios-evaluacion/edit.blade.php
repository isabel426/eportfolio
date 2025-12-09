@extends('landed.master')



    @section('content')
  <h2>Editar Criterios de evaluacion con id: {{$id}}</h2>
          <div class="row" style="margin-top:40px">
        <div class="offset-md-3 col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Modificar criterio de evaluacion
                </div>
                <div class="card-body" style="padding:30px">

                    <form action="{{ action([App\Http\Controllers\CriteriosEvaluacionController::class, 'postEdit'],  ['id' => $id]) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="resultado_aprendizaje_id">Id del resultado de aprendizaje </label>
                            <input type="text" name="resultado_aprendizaje_id" id="resultado_aprendizaje_id" value="{{$criterios_evaluacion->resultado_aprendizaje_id}}">
                        </div>
                        <div class ="form-group">
                            <label for="codigo">Codigo del resultado de aprendizaje </label>
                            <input type="text" name="codigo" id="codigo" value="{{$criterios_evaluacion->codigo}}">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" name="descripcion" id="descripcion" value="{{$criterios_evaluacion->descripcion}}">
                        </div>

                        <div class="form-group">
                            <label for="peso_porcentaje">Peso Porcentaje</label>
                            <input type="text" name="peso_porcentaje" id="peso_porcentaje" value="{{$criterios_evaluacion->peso_porcentaje}}">
                        </div>
                        <div class="form-group">
                            <label for="orden">Orden</label>
                            <input type="text" name="orden" id="orden" value="{{$criterios_evaluacion->orden}}">
                        </div>


                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary" style="padding:8px 100px;margin-top:25px;">
                                Modificar criterio de evaluación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     @endsection
