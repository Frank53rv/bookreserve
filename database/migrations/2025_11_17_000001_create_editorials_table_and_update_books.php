<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('editorials', function (Blueprint $table) {
            $table->id('id_editorial');
            $table->string('nombre', 150);
            $table->string('pais', 120)->nullable();
            $table->string('sitio_web', 255)->nullable();
            $table->string('contacto', 150)->nullable();
            $table->timestamps();
        });

        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('id_editorial')
                ->nullable()
                ->after('autor')
                ->constrained('editorials', 'id_editorial')
                ->nullOnDelete();
            $table->decimal('precio_venta', 10, 2)->default(0)->after('stock_actual');
        });

        if (Schema::hasColumn('books', 'editorial')) {
            $editorialNames = DB::table('books')
                ->whereNotNull('editorial')
                ->where('editorial', '!=', '')
                ->distinct()
                ->pluck('editorial');

            $editorialMap = [];

            foreach ($editorialNames as $name) {
                $editorialMap[$name] = DB::table('editorials')->insertGetId([
                    'nombre' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $bookQuery = DB::table('books')
                ->select('id_libro', 'editorial')
                ->whereNotNull('editorial')
                ->where('editorial', '!=', '')
                ->get();

            foreach ($bookQuery as $book) {
                $editorialId = $editorialMap[$book->editorial] ?? null;

                if ($editorialId) {
                    DB::table('books')
                        ->where('id_libro', $book->id_libro)
                        ->update(['id_editorial' => $editorialId]);
                }
            }

            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('editorial');
            });
        }
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('editorial', 100)->nullable()->after('autor');
        });

        $books = DB::table('books')
            ->select('books.id_libro', 'editorials.nombre')
            ->leftJoin('editorials', 'editorials.id_editorial', '=', 'books.id_editorial')
            ->get();

        foreach ($books as $book) {
            DB::table('books')
                ->where('id_libro', $book->id_libro)
                ->update(['editorial' => $book->nombre]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_editorial');
            $table->dropColumn('precio_venta');
        });

        Schema::dropIfExists('editorials');
    }
};
