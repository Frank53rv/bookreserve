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
        Schema::create('return_headers', function (Blueprint $table) {
            $table->id('id_devolucion');
            $table->foreignId('id_cliente')
                ->constrained('clients', 'id_cliente')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('fecha_devolucion');
            $table->enum('estado', ['Completa', 'Parcial'])->default('Completa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_headers');
    }
};
