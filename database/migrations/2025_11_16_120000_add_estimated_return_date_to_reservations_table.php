<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservation_headers', function (Blueprint $table) {
            $table->dateTime('fecha_estimada_devolucion')->nullable()->after('fecha_reserva');
        });
    }

    public function down(): void
    {
        Schema::table('reservation_headers', function (Blueprint $table) {
            $table->dropColumn('fecha_estimada_devolucion');
        });
    }
};
