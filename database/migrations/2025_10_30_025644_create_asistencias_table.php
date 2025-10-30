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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->foreignId('alumno_id')->constrained('alumnos', 'id_alumno')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('nivel_id')->constrained('niveles', 'id_nivel')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('asistencia', ['A', 'F']);
            $table->timestamps();

            $table->index('alumno_id');
            $table->index('nivel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias_tables');
    }
};