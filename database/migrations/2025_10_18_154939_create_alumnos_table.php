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
            $table->id('id_alumno');
            $table->char('matricula', 10)
                ->unique()
                ->nullable();
            $table->string('nombre', 50);
            $table->string('ap_paterno', 50)
                ->nullable();
            $table->string('ap_materno', 50)
                ->nullable();
            $table->integer('edad')
                ->nullable();
            $table->foreignId('carrera_id')
                ->nullable()
                ->constrained('carreras', 'id_carrera');
            $table->string('telefono', 15)
                ->nullable();
            $table->enum('sexo', ['M', 'F'])
                ->nullable();
            $table->foreignId('nivel_id')
                ->nullable()
                ->constrained('niveles', 'id_nivel');
            $table->foreignId('estatus_id')
                ->nullable()
                ->constrained('estatus_alumnos', 'id_estatus_alumno');
            $table->foreignId('user_id')
                ->constrained('users', 'id_user');
            $table->foreignId('expediente_id')
                ->nullable()
                ->constrained('expedientes', 'id_expediente');
            $table->foreignId('nota_id')
                ->nullable()
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