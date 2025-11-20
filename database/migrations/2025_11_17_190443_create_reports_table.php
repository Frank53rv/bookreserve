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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id_reporte');
            $table->string('nombre', 200);
            $table->enum('tipo', ['ventas', 'inventario', 'clientes', 'reservas', 'financiero', 'movimientos']);
            $table->enum('formato', ['pdf', 'excel']);
            $table->json('parametros')->nullable(); // Filtros y configuración
            $table->string('archivo_path')->nullable(); // Ruta del archivo generado
            $table->string('generado_por', 100)->nullable(); // Usuario que lo generó
            $table->timestamp('fecha_generacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
