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
        Schema::table('administradora_planos', function (Blueprint $table) {
            // Adiciona a coluna que poderá ser NULL
            $table->unsignedBigInteger('tabela_origens_id')->nullable()->after('administradora_id');
            // Define a chave estrangeira
            $table->foreign('tabela_origens_id')->references('id')->on('tabela_origens')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administradora_planos', function (Blueprint $table) {
            //
        });
    }
};
