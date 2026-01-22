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
        Schema::dropIfExists('modulos_formativos');

        Schema::create('modulos_formativos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('ciclo_formativo_id');
            $table->foreign('ciclo_formativo_id')->references('id')->on('ciclos_formativos')->onDelete('cascade');
            $table->string('nombre');
            $table->string('codigo');
            $table->integer('horas_totales');
            $table->string('curso_escolar');
            $table->string('centro');
            $table->unsignedBigInteger('docente_id');
            $table->foreign('docente_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos_formativos');
    }
};


/*
     FAMILIAS_PROFESIONALES {
        bigint id PK
        varchar nombre
        varchar codigo UK
        text descripcion
        timestamp created_at
        timestamp updated_at
    }


    CICLOS_FORMATIVOS {
        bigint id PK
        bigint familia_profesional_id FK
        varchar nombre
        varchar codigo UK
        enum grado
        text descripcion
        timestamp created_at
        timestamp updated_at
    }

    MODULOS_FORMATIVOS {
        bigint id PK
        bigint ciclo_formativo_id FK
        varchar nombre
        varchar codigo
        int horas_totales
        varchar curso_escolar
        varchar centro
        bigint docente_id FK
        text descripcion
        timestamp created_at
        timestamp updated_at
    }

*/
