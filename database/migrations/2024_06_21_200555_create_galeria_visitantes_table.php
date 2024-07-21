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
        Schema::create('galeria_visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('photo_path')->default("")->nullable();
            $table->string("detalle")->default("")->nullable();
            $table->unsignedBigInteger("visitante_id")->nullable();
            $table->timestamps();
            $table->foreign( "visitante_id" )->references( "id" )->on("visitantes")->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeria_visitantes');
    }
};