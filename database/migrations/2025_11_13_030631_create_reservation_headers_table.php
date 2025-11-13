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
        Schema::create('reservation_headers', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->foreignId('id_cliente')
                ->constrained('clients', 'id_cliente')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('fecha_reserva');
            $table->enum('estado', ['Pendiente', 'Retirado', 'Cancelado'])->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_headers');
    }
};
