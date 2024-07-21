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
        Schema::create('galeria_ingresos', function (Blueprint $table) {
            $table->id();
            $table->string('photo_path')->default("")->nullable();
            $table->string("detalle")->default("")->nullable();
            $table->boolean("isAutorizado")->default(false);
            $table->unsignedBigInteger("ingreso_id")->default(0)->nullable();
            $table->timestamps();
            $table->foreign( "ingreso_id" )->references( "id" )->on("ingresos")->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeria_ingresos');
    }
};