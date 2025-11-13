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
        Schema::create('return_details', function (Blueprint $table) {
            $table->id('id_detalle_devolucion');
            $table->foreignId('id_devolucion')
                ->constrained('return_headers', 'id_devolucion')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('id_libro')
                ->constrained('books', 'id_libro')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('cantidad_devuelta');
            $table->timestamps();

            $table->unique(['id_devolucion', 'id_libro']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};
