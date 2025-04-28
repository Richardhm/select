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
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('trial_ends_at')->nullable();
            $table->string('folder')->nullable();
            $table->integer('subscription_id')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('cupom_id')->unsigned()->nullable();
            $table->bigInteger('tipo_plano_id')->unsigned()->nullable();
            $table->decimal('preco_base', 10, 2);
            $table->integer('emails_permitidos')->default(1);
            $table->integer('emails_extra')->default(0);
            $table->decimal('preco_total', 10, 2);
            $table->string('status')->default('ativo');
            $table->dateTime('last_payment')->nullable();
            $table->dateTime('next_charge')->nullable();
            $table->dateTime('last_updated')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cupom_id')->references('id')->on('cupons')->onDelete('set null');
            $table->foreign('tipo_plano_id')->references('id')->on('tipos_planos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
