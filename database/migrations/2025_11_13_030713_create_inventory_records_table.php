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
        Schema::create('inventory_records', function (Blueprint $table) {
            $table->id('id_inventario');
            $table->foreignId('id_libro')
                ->constrained('books', 'id_libro')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->dateTime('fecha_ingreso');
            $table->integer('cantidad_ingresada');
            $table->string('proveedor', 100)->nullable();
            $table->string('observacion', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_records');
    }
};
