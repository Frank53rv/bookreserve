<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_categories(): void
    {
        $this->makeCategory(['nombre' => 'Historia']);
        $this->makeCategory(['nombre' => 'Novela']);

        $response = $this->getJson('/api/categories');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_category(): void
    {
        $payload = [
            'nombre' => 'Ciencia',
            'descripcion' => 'Libros de ciencia',
        ];

        $response = $this->postJson('/api/categories', $payload);

        $response->assertCreated()->assertJsonFragment($payload);
        $this->assertDatabaseHas('categories', $payload);
    }

    public function test_can_show_category(): void
    {
        $category = $this->makeCategory(['nombre' => 'Infantil']);

        $response = $this->getJson("/api/categories/{$category->id_categoria}");

        $response->assertOk()->assertJsonFragment([
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Infantil',
        ]);
    }

    public function test_can_update_category(): void
    {
        $category = $this->makeCategory(['nombre' => 'Antiguo']);

        $response = $this->putJson("/api/categories/{$category->id_categoria}", [
            'nombre' => 'Renovado',
            'descripcion' => 'Categoria actualizada',
        ]);

        $response->assertOk()->assertJsonFragment([
            'nombre' => 'Renovado',
            'descripcion' => 'Categoria actualizada',
        ]);

        $this->assertDatabaseHas('categories', [
            'id_categoria' => $category->id_categoria,
            'nombre' => 'Renovado',
        ]);
    }

    public function test_can_delete_category(): void
    {
        $category = $this->makeCategory();

        $response = $this->deleteJson("/api/categories/{$category->id_categoria}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('categories', [
            'id_categoria' => $category->id_categoria,
        ]);
    }
}
