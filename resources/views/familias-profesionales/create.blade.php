@extends('landed.master')



    @section('content')
        <h2>Create Familias Profesionales</h2>

          <div class="row" style="margin-top:40px">
        <div class="offset-md-3 col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Añadir familias profesionales
                </div>
                <div class="card-body" style="padding:30px">

                    <form action="{{ action([App\Http\Controllers\FamiliasProfesionalesController::class, 'store']) }}" method="POST">

                        @csrf


                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>


                        <div class="form-group">

                            <label for="codigo">Codigo</label>
                            <input type="text" name="codigo" id="codigo">
                        </div>


                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary" style="padding:8px 100px;margin-top:25px;">
                                Añadir familia profesional
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    @endsection
