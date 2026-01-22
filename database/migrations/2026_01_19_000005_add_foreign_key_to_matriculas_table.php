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
        Schema::table('matriculas', function (Blueprint $table) {
            $table->unsignedBigInteger('modulo_formativo_id')->after('estudiante_id')->nullable();
            $table->foreign('modulo_formativo_id')->references('id')->on('modulos_formativos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropForeign(['modulo_formativo_id']);
            $table->dropColumn('modulo_formativo_id');
        });
    }
};
