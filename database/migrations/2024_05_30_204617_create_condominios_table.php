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
        Schema::create('condominios', function (Blueprint $table) {
            $table->id();
            $table->string('propietario')->default('')->nullable();
            $table->string('razonSocial')->default('')->nullable();
            $table->string('nit')->default('')->nullable();
            $table->unsignedBigInteger('cantidad_viviendas')->default(0)->nullable();
            $table->unsignedBigInteger('perfil_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->foreign( 'perfil_id' )->references( 'id' )->on('perfils')->noActionOnDelete()->noActionOnUpdate();
            $table->foreign( 'user_id' )->references( 'id' )->on('users')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condominios');
    }
};