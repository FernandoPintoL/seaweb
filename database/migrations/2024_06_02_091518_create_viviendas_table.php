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
        Schema::create('viviendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('condominio_id')->nullable();
            $table->boolean('vivienda_ocupada')->default(false)->nullable();
            $table->string('nroVivienda')->default("C1")->nullable();
            $table->string('detalle')->default("")->nullable();
            $table->unsignedInteger('nroEspacios')->default(1)->nullable();
            $table->unsignedBigInteger('tipo_vivienda_id')->default(0)->nullable();
            $table->timestamps();
            $table->foreign( 'condominio_id' )->references( 'id' )->on('condominios')->noActionOnDelete()->noActionOnUpdate();
            $table->foreign( 'tipo_vivienda_id' )->references( 'id' )->on('tipo_viviendas')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viviendas');
    }
};