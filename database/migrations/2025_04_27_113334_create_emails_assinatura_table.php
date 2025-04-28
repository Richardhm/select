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
        Schema::create('emails_assinatura', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assinatura_id');
            $table->string('email')->unique(); // Definindo o email como chave única
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_administrador')->default(false);
            $table->timestamps();

            // Definindo as chaves estrangeiras
            $table->foreign('assinatura_id')->references('id')->on('assinaturas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails_assinatura');
    }
};
