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
            $table->char('grupo', 3);
            $table->string('aula', 255);
            $table->foreignId('profesor_id')->constrained('profesores', 'id_profesor');
            $table->integer('cupo_max');
            $table->foreignId('periodo_id')->constrained('periodos', 'id_periodo');
            $table->string('modalidad', 50);
            $table->string('horario', 50);
            $table->timestamps();

            $table->index('profesor_id');
            $table->index('periodo_id');
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