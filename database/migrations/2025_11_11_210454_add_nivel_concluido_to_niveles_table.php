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
        Schema::table('niveles', function (Blueprint $table) {
            // Añade la columna 'nivel_concluido'
            // boolean se traduce como TINYINT(1) en MySQL, perfecto para 0 (falso) o 1 (verdadero)
            $table->boolean('nivel_concluido')
                  ->default(false) // Por defecto, todos los grupos están "activos" (no concluidos)
                  ->after('parcial_3'); // La coloca después de la columna parcial_3 (opcional, por orden)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('niveles', function (Blueprint $table) {
            // Esto es para poder deshacer el cambio si es necesario (migrate:rollback)
            $table->dropColumn('nivel_concluido');
        });
    }
};