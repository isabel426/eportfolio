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
        Schema::table('criterios_evaluacion', function (Blueprint $table) {
            $table->foreign('resultado_aprendizaje_id')->references('id')->on('resultados_aprendizaje')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criterios_evaluacion', function (Blueprint $table) {
            $table->dropForeign(['resultado_aprendizaje_id']);
            $table->dropColumn('resultado_aprendizaje_id');
        });
    }
};
