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
        Schema::create('documentos_expedientes', function (Blueprint $table) {
            $table->id('id_documento_expediente');
            $table->foreignId('expediente_id')
                ->constrained('expedientes', 'id_expediente');
            $table->integer('nivel');
            $table->text('const_na');
            $table->text('comp_pago');
            $table->text('lin_captura');
            $table->timestamps();

            $table->index('expediente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_expedientes');
    }
};