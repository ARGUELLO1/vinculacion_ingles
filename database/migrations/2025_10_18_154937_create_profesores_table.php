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
        Schema::create('profesores', function (Blueprint $table) {
            $table->id('id_profesor');
            $table->string('nombre', 50);
            $table->string('ap_paterno', 50);
            $table->string('ap_materno', 50);
            $table->integer('edad');
            $table->foreignId('estado_civil_id')
                ->constrained('estados_civiles', 'id_estado_civil');
            $table->enum('sexo', ['M', 'F']);
            $table->string('calle');
            $table->string('numero', 20);
            $table->string('colonia');
            $table->char('codigo_postal', 5);
            $table->foreignId('municipio_id')
                ->constrained('municipios', 'id_municipio');
            $table->string('estado', 50);
            $table->char('rfc', 13);
            $table->enum('estatus', ['activo', 'inactivo']);
            $table->foreignId('user_id')
                ->constrained('users', 'id_user');
            $table->timestamps();

            //Indices
            $table->index('estado_civil_id');
            $table->index('municipio_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};