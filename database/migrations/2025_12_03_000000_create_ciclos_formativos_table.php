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
        Schema::create('ciclos_formativos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('familia_profesional_id')->nullable();
            $table->string('nombre', 255);
            $table->string('codigo', 50);
            $table->enum('grado', ['G.M.', 'G.S.', 'C.E. (G.M.)', 'C.E. (G.S.)', 'BÁSICA']);
            $table->string('descripcion')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos_formativos');
    }
};
