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
        Schema::create('administradores', function (Blueprint $table) {
            $table->id('id_admin');
            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('nombre', 50)
                ->nullable();
            $table->string('ap_paterno', 50)
                ->nullable();
            $table->string('ap_materno', 50)
                ->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};