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
        Schema::create('detalle_no_autorizados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ingreso_id')->nullable();
            $table->text('detalle_no_autorizado')->default("")->nullable();
            $table->timestamps();
            $table->foreign( "ingreso_id" )->references( "id" )->on("ingresos")->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_no_autorizados');
    }
};
