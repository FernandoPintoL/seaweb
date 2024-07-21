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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->default('')->nullable();
            $table->string('photo_path')->default('')->nullable();
            $table->string('detalle')->default('')->nullable();
            $table->enum('tipo_vehiculo', ['automóvil','motocicleta','camión', 'bicicleta','otro'])->default('automóvil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};