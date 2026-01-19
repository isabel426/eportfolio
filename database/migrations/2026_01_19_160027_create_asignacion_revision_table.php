<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asignaciones_revision', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('evidencia_id');
            $table->foreign('evidencia_id')->references('id')->on('evidencias');

            $table->unsignedBigInteger('revisor_id');
            $table->foreign('revisor_id')->references('id')->on('users');

            $table->unsignedBigInteger('asignado_por_id');
            $table->foreign('asignado_por_id')->references('id')->on('users');

            $table->date('fecha_limite');
            $table->enum('estado',['pendiente', 'expirada', 'completada' ]);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones_revision');
    }
};
