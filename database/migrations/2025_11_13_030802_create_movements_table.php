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
        Schema::create('movements', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->foreignId('id_cliente')
                ->constrained('clients', 'id_cliente')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('id_libro')
                ->constrained('books', 'id_libro')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->enum('tipo_movimiento', ['Entrada', 'Salida', 'Devolucion']);
            $table->dateTime('fecha_movimiento');
            $table->integer('cantidad');
            $table->string('observacion', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
