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
        Schema::create('visitantes', function (Blueprint $table) {
            $table->id();
            $table->string('profile_photo_path', 2048)->default('')->nullable();
            $table->unsignedBigInteger('perfil_id')->nullable();
            $table->boolean('is_permitido')->default(true)->nullable(); //Esta es mi lista negra
            $table->longText('description_is_no_permitido')->default('')->nullable();
            $table->timestamps();
            $table->foreign( 'perfil_id' )->references( 'id' )->on('perfils')->noActionOnDelete()->noActionOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};