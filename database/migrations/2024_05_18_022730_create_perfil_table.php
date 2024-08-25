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
        Schema::create('perfils', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->nullable();
            $table->string('email')->default('')->nullable();
            $table->string('nroDocumento')->default('')->nullable();
            $table->string('direccion')->default('')->nullable();
            $table->string('celular')->default('')->nullable();
            $table->unsignedBigInteger('tipo_documento_id')->nullable();
            $table->timestamps();
            $table->foreign( 'tipo_documento_id' )->references( 'id' )->on( 'tipo_documentos' )->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfils');
    }
};

// "id","condominio_id","vivienda_ocupada","nroVivienda","detalle","nroEspacios","tipo_vivienda_id","created_at","updated_at"
// "1","1",False,"A-18",NULL,5,"1","2024-08-07 14:59:07","2024-08-15 18:22:42"