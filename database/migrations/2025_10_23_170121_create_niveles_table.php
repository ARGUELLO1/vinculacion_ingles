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
        Schema::create('niveles', function (Blueprint $table) {
            $table->id('id_nivel');
            $table->char('nivel', 3);
            $table->char('nombre_grupo', 4);
            $table->string('aula');
            $table->integer('cupo_max');
            $table->string('horario');
            $table->foreignId('profesor_id')->constrained('profesores', 'id_profesor');
            $table->foreignId('periodo_id')->constrained('periodos', 'id_periodo');
            $table->foreignId('modalidad_id')->constrained('modalidades', 'id_modalidad');
            $table->enum('parcial_1', ['1', '0'])->nullable();
            $table->enum('parcial_2', ['1', '0'])->nullable();
            $table->enum('parcial_3', ['1', '0'])->nullable();
            $table->enum('nivel_concluido', ['1', '0'])->nullable();
            $table->timestamps();

            $table->index('profesor_id');
            $table->index('periodo_id');
            $table->index('modalidad_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveles');
    }
};