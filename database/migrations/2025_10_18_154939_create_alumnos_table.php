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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id('matricula');
            $table->string('nombre', 50);
            $table->string('ap_paterno', 50);
            $table->string('ap_materno', 50);
            $table->integer('edad');
            $table->foreignId('carrera_id')
                ->constrained('carreras', 'id_carrera');
            $table->string('telefono', 15);
            $table->enum('sexo', ['M', 'F']);
            $table->foreignId('nivel_id')
                ->constrained('niveles', 'id_nivel');
            $table->foreignId('estatus_id')
                ->constrained('estatus_alumnos', 'id_estatus_alumno');
            $table->foreignId('user_id')
                ->constrained('users', 'id_user');
            $table->foreignId('expediente_id')
                ->constrained('expedientes', 'id_expediente');
            $table->foreignId('nota_id')
                ->constrained('notas', 'id_nota');
            $table->timestamps();

            //Indices
            $table->index('carrera_id');
            $table->index('nivel_id');
            $table->index('estatus_id');
            $table->index('user_id');
            $table->index('expediente_id');
            $table->index('nota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};