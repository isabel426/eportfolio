@extends('landed.master')



    @section('content')
        <h2>Create criterios de evaluacion</h2>

          <div class="row" style="margin-top:40px">
        <div class="offset-md-3 col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Añadir criterios de evaluacion
                </div>
                <div class="card-body" style="padding:30px">

                    <form action="{{ action([App\Http\Controllers\CriteriosEvaluacionController::class, 'postCreate']) }}" method="POST">

                        @csrf


                        <div class="form-group">
                            <label for="resultado_aprendizaje_id">Id del resultado de aprendizaje </label>
                            <input type="text" name="resultado_aprendizaje_id" id="resultado_aprendizaje_id" class="form-control">
                        </div>
                        <div class ="form-group">
                            <label for="codigo">Codigo </label>
                            <input type="text" name="codigo" id="codigo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" name="descripcion" id="descripcion" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="peso_porcentaje">Peso Porcentaje</label>
                            <input type="text" name="peso_porcentaje" id="peso_porcentaje" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="orden">Orden</label>
                            <input type="text" name="orden" id="orden" class="form-control">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary" style="padding:8px 100px;margin-top:25px;">
                                Añadir criterio de evaluación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    @endsection
