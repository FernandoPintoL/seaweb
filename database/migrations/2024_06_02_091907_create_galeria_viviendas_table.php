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
        Schema::create('galeria_viviendas', function (Blueprint $table) {
            $table->id();
            $table->string('photo_path')->default("")->nullable();
            $table->string("detalle")->default("")->nullable();
            $table->unsignedBigInteger("vivienda_id")->default(0)->nullable();
            $table->timestamps();
            $table->foreign( "vivienda_id" )->references( "id" )->on("viviendas")->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeria_viviendas');
    }
};
