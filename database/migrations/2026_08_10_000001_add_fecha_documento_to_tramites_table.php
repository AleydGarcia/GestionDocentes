<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            $table->date('fecha_documento')->nullable()->after('fecha_fin');
        });

        DB::statement('UPDATE tramites SET fecha_documento = DATE(created_at) WHERE fecha_documento IS NULL');
    }

    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            $table->dropColumn('fecha_documento');
        });
    }
};