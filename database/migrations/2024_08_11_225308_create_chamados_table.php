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
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('solicitante');
            $table->text('descricao');
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('tecnico_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('tecnico_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};
