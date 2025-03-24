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
        Schema::create('anotaciones', function (Blueprint $table) {
            $table->id();

            // Relación con la solicitud
            $table->unsignedBigInteger('solicitud_id');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->onDelete('cascade');

            // Relación con el técnico que hace la anotación
            $table->unsignedBigInteger('tecnico_id')->nullable();
            $table->foreign('tecnico_id')->references('id')->on('users')->onDelete('set null');

            // Campos de contenido
            $table->text('descripcion')->nullable();
            $table->text('material_usado')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anotaciones');
    }
};
