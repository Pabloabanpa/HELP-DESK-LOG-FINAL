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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            // Clave foránea al usuario que crea la solicitud
            $table->unsignedBigInteger('solicitante');
            $table->foreign('solicitante')->references('id')->on('users')->onDelete('cascade');

            // Clave foránea al técnico asignado (nullable)
            $table->unsignedBigInteger('tecnico')->nullable();
            $table->foreign('tecnico')->references('id')->on('users')->onDelete('set null');

            // Relación con equipo (si manejas una tabla equipos)
            $table->unsignedBigInteger('equipo_id')->nullable(); // relación opcional
            // $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('set null');

            $table->unsignedBigInteger('tipo_problema')->nullable();
            $table->foreign('tipo_problema')->references('id')->on('tipo_problemas')->onDelete('cascade');

            $table->text('descripcion')->nullable();
            $table->string('archivo')->nullable(); // ruta o nombre del archivo adjunto
            $table->string('estado')->default('pendiente');
            $table->string('prioridad')->default('media'); // prioridad de la solicitud (baja, media, alta)


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
