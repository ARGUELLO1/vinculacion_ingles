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
        Schema::create('notas', function (Blueprint $table) {
            $table->id('id_nota');
            $table->foreignId('alumno_id')->constrained('alumnos', 'id_alumno');
            $table->foreignId('nivel_id')->constrained('niveles', 'id_nivel');
            $table->decimal('nota_parcial_1', 5, 1);
            $table->decimal('nota_parcial_2', 5, 1)->nullable();
            $table->decimal('nota_parcial_3', 5, 1)->nullable();
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
        Schema::dropIfExists('notas');
    }
};
