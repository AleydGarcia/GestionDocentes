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
        Schema::create('evidencias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tramite_id')
            ->constrained('tramites')
            ->cascadeOnDelete();
        $table->string('nombre_archivo');
        $table->string('ruta');
        $table->date('fecha_carga');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidencias');
    }
};
