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
        Schema::create('books', function (Blueprint $table) {
            $table->id('id_libro');
            $table->string('titulo', 150);
            $table->string('autor', 100);
            $table->string('editorial', 100)->nullable();
            $table->year('anio_publicacion')->nullable();
            $table->string('isbn', 30)->nullable()->unique();
            $table->foreignId('id_categoria')
                ->constrained('categories', 'id_categoria')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->integer('stock_actual')->default(0);
            $table->enum('estado', ['Disponible', 'No disponible'])->default('Disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
