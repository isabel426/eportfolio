@extends('landed.master')



    @section('content')
  <h2>Editar Familias Profesionales con id: {{$id}}</h2>
          <div class="row" style="margin-top:40px">
        <div class="offset-md-3 col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Modificar familia profesional
                </div>
                <div class="card-body" style="padding:30px">

                    <form action="{{ action([App\Http\Controllers\FamiliasProfesionalesController::class, 'update'],  ['id' => $id]) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"  value="{{$familias_profesionales['nombre']}}">
                        </div>

                        <div class="form-group">
                            <label for="codigo">Codigo</label>
                            <input type="text" name="codigo" id="codigo" value="{{$familias_profesionales['codigo']}}">
                        </div>


                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary" style="padding:8px 100px;margin-top:25px;">
                                Modificar familia profesional
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     @endsection
