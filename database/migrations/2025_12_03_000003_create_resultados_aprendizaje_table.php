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
        Schema::create('resultados_aprendizaje', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("modulo_formativo_id")->nullable();
            $table->string("codigo",50);
            $table->text("descripción");
            $table->tinyInteger("peso_porcentaje")->nullable();
            $table->integer("orden")->check("orden>=1");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_aprendizaje');
    }
};
