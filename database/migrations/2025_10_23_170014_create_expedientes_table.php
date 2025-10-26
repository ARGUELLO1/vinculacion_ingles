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
            $table->integer('nivel')->nullable();
            $table->text('lin_captura')->nullable();
            $table->text('soli_aspirante')->nullable();
            $table->text('acta_nac')->nullable();
            $table->text('comp_estu')->nullable();
            $table->text('ine')->nullable();
            $table->text('comp_pago')->nullable();
            $table->text('lin_captura_t')->nullable();
            $table->date('fecha_pago');
            $table->date('fecha_entrega');
            $table->timestamps();

            $table->index('nivel');
            $table->index('fecha_pago');
            $table->index('fecha_entrega');
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