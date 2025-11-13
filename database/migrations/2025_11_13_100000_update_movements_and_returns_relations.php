<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_headers', function (Blueprint $table) {
            $table->foreignId('id_reserva')
                ->nullable()
                ->after('id_cliente')
                ->constrained('reservation_headers', 'id_reserva')
                ->nullOnDelete();
        });

        Schema::table('return_details', function (Blueprint $table) {
            $table->foreignId('id_detalle_reserva')
                ->nullable()
                ->after('id_devolucion')
                ->constrained('reservation_details', 'id_detalle_reserva')
                ->nullOnDelete();
        });

        Schema::table('movements', function (Blueprint $table) {
            $table->foreignId('id_reserva')
                ->nullable()
                ->after('id_libro')
                ->constrained('reservation_headers', 'id_reserva')
                ->nullOnDelete();
            $table->foreignId('id_devolucion')
                ->nullable()
                ->after('id_reserva')
                ->constrained('return_headers', 'id_devolucion')
                ->nullOnDelete();
            $table->json('metadata')->nullable()->after('observacion');
        });

        Schema::create('movement_logs', function (Blueprint $table) {
            $table->id('id_log_movimiento');
            $table->foreignId('id_movimiento')
                ->constrained('movements', 'id_movimiento')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('descripcion', 255);
            $table->json('contexto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_logs');

        Schema::table('movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_devolucion');
            $table->dropConstrainedForeignId('id_reserva');
            $table->dropColumn('metadata');
        });

        Schema::table('return_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_detalle_reserva');
        });

        Schema::table('return_headers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_reserva');
        });
    }
};
