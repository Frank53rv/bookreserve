<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_batches', function (Blueprint $table) {
            $table->id('id_lote');
            $table->foreignId('id_proveedor')
                ->nullable()
                ->constrained('suppliers', 'id_proveedor')
                ->nullOnDelete();
            $table->string('codigo_lote', 50)->unique();
            $table->dateTime('fecha_recepcion');
            $table->string('documento_referencia', 100)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_batch_items', function (Blueprint $table) {
            $table->id('id_detalle_lote');
            $table->foreignId('id_lote')
                ->constrained('purchase_batches', 'id_lote')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('id_libro')
                ->constrained('books', 'id_libro')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('cantidad');
            $table->decimal('costo_unitario', 10, 2);
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamps();

            $table->unique(['id_lote', 'id_libro']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_batch_items');
        Schema::dropIfExists('purchase_batches');
    }
};
