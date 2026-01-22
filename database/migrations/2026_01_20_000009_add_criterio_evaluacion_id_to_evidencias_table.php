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
        Schema::table('evidencias', function (Blueprint $table) {

            $table->unsignedBigInteger('criterio_evaluacion_id')->nullable();

            $table->foreign('criterio_evaluacion_id')
                ->references('id')
                ->on('criterios_evaluacion')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evidencias', function (Blueprint $table) {
            $table->dropForeign(['criterio_evaluacion_id']);
            $table->dropColumn('criterio_evaluacion_id');
        });
    }
};
