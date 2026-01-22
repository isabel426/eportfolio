<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('criterio_evaluacion_id');
            $table->datetime('fecha_apertura');
            $table->datetime('fecha_cierre');
            $table->boolean('activo')->default(false);
            $table->text('enunciado')->nullable();
            $table->timestamps();

            $table->foreign('criterio_evaluacion_id')->references('id')->on('criterios_evaluacion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}
