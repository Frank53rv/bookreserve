<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('nombre_comercial', 150);
            $table->string('contacto', 150)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('correo', 150)->nullable();
            $table->string('identificacion', 60)->nullable();
            $table->string('condiciones_pago', 120)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
