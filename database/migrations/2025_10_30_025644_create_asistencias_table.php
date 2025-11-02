<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencia');
            $table->foreignId('alumno_id')->constrained('alumnos', 'id_alumno');
            $table->foreignId('nivel_id')->constrained('niveles', 'id_nivel');
            $table->integer('parcial');
            $table->date('fecha');
            $table->enum('asistencia', ['A', 'F']);
            $table->timestamps();

            $table->unique(['alumno_id', 'nivel_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};