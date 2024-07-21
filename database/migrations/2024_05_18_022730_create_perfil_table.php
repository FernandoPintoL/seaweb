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
            $table->string('email')->nullable()->unique();
            $table->string('nroDocumento')->default('')->nullable()->unique();
            $table->string('direccion')->nullable();
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