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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id('id_expediente');
            $table->foreignId('alumno_id')->constrained('alumnos', 'id_alumno');
            $table->foreignId('nivel_id')->constrained('niveles', 'id_nivel');
            $table->string('ruta_expediente');
            $table->text('lin_captura_t');
            $table->date('fecha_pago');
            $table->date('fecha_entrega');
            $table->timestamps();

            $table->index('fecha_pago');
            $table->index('fecha_entrega');
            $table->index('ruta_expediente');
            $table->index(['alumno_id', 'nivel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
