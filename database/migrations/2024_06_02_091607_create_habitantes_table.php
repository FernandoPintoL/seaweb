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
        Schema::create('habitantes', function (Blueprint $table) {
            $table->id();
            $table->boolean('isDuenho')->default(true)->nullable();
            $table->boolean('isDependiente')->default(false)->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('perfil_id')->nullable();
            $table->unsignedBigInteger('vivienda_id')->nullable();
            $table->timestamps();
            $table->string('profile_photo_path', 2048)->default('')->nullable();
            $table->foreign( 'perfil_id' )->references( 'id' )->on('perfils')->noActionOnDelete()->noActionOnUpdate();
            $table->foreign( 'responsable_id' )->references( 'id' )->on('habitantes')->noActionOnDelete()->noActionOnUpdate();
            $table->foreign( 'vivienda_id' )->references( 'id' )->on('viviendas')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitantes');
    }
};