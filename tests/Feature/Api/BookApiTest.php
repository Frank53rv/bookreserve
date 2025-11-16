<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_books(): void
    {
        $this->makeBook(['titulo' => 'Libro A']);
        $this->makeBook(['titulo' => 'Libro B']);

        $response = $this->getJson('/api/books');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_book(): void
    {
        $category = $this->makeCategory();
        $editorial = $this->makeEditorial();

        $payload = [
            'titulo' => 'Nuevo Libro',
            'autor' => 'Autor Prueba',
            'id_editorial' => $editorial->id_editorial,
            'anio_publicacion' => 2024,
            'isbn' => 'ISBN1234567890',
            'id_categoria' => $category->id_categoria,
            'stock_actual' => 7,
            'precio_venta' => 59.9,
            'estado' => 'Disponible',
        ];

        $response = $this->postJson('/api/books', $payload);

        $response->assertCreated()->assertJsonFragment([
            'titulo' => 'Nuevo Libro',
            'autor' => 'Autor Prueba',
        ]);

        $this->assertDatabaseHas('books', [
            'titulo' => 'Nuevo Libro',
            'id_categoria' => $category->id_categoria,
            'id_editorial' => $editorial->id_editorial,
        ]);
    }

    public function test_can_show_book(): void
    {
        $book = $this->makeBook(['titulo' => 'Detalle Libro']);

        $response = $this->getJson("/api/books/{$book->id_libro}");

        $response->assertOk()->assertJsonFragment([
            'id_libro' => $book->id_libro,
            'titulo' => 'Detalle Libro',
        ]);
    }

    public function test_can_update_book(): void
    {
        $book = $this->makeBook(['titulo' => 'Actualizable']);

        $response = $this->putJson("/api/books/{$book->id_libro}", [
            'titulo' => 'Actualizado',
            'autor' => 'Autor Actualizado',
        ]);

        $response->assertOk()->assertJsonFragment([
            'titulo' => 'Actualizado',
            'autor' => 'Autor Actualizado',
        ]);

        $this->assertDatabaseHas('books', [
            'id_libro' => $book->id_libro,
            'titulo' => 'Actualizado',
        ]);
    }

    public function test_can_delete_book(): void
    {
        $book = $this->makeBook();

        $response = $this->deleteJson("/api/books/{$book->id_libro}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('books', [
            'id_libro' => $book->id_libro,
        ]);
    }
}
