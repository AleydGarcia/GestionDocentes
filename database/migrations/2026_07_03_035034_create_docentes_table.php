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
    Schema::create('docentes', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('especialidad');
        $table->string('domicilio');
        $table->string('localidad');
        $table->string('celular', 20);
        $table->string('estado_civil');
        $table->string('rfc', 13)->unique();
        $table->string('curp', 18)->unique();
        $table->string('ultimo_grado_estudios');
        $table->integer('numero_pensiones')->default(0);
        $table->string('clave_presupuestal')->unique();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
