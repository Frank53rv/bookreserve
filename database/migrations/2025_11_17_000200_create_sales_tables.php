<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_headers', function (Blueprint $table) {
            $table->id('id_venta');
            $table->foreignId('id_cliente')
                ->nullable()
                ->constrained('clients', 'id_cliente')
                ->nullOnDelete();
            $table->dateTime('fecha_venta');
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('estado', ['Pendiente', 'Pagada', 'Anulada'])->default('Pendiente');
            $table->string('metodo_pago', 80)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_details', function (Blueprint $table) {
            $table->id('id_detalle_venta');
            $table->foreignId('id_venta')
                ->constrained('sale_headers', 'id_venta')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('id_libro')
                ->constrained('books', 'id_libro')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_details');
        Schema::dropIfExists('sale_headers');
    }
};
