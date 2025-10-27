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
        Schema::create('documentos_niveles', function (Blueprint $table) {
            $table->id('id_documento');
            $table->foreignId('nivel_id')->constrained('niveles', 'id_nivel');
            $table->string('tipo_doc', 100);
            $table->text('ruta_doc');
            $table->timestamps();

            $table->index('nivel_id');
        });

        Schema::create('documentos_profesores', function (Blueprint $table) {
            $table->id('id_documento_profesor');
            $table->foreignId('nivel_id')->constrained('niveles', 'id_nivel');
            $table->string('tipo_doc', 100);
            $table->text('ruta_doc');
            $table->timestamps();

            $table->index('nivel_id');
        });

        Schema::create('documentos_expedientes', function (Blueprint $table) {
            $table->id('id_documento_expediente');
            $table->foreignId('nivel_id')->constrained('niveles', 'id_nivel');
            $table->string('tipo_doc', 100);
            $table->text('ruta_doc');
            $table->timestamps();

            $table->index('nivel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_niveles');
        Schema::dropIfExists('documentos_profesores');
        Schema::dropIfExists('documentos_expedientes');
    }
};
