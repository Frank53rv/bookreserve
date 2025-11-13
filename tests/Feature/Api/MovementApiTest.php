<?php

namespace Tests\Feature\Api;

use App\Models\Movement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class MovementApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_movements(): void
    {
        $this->makeMovement();
        $this->makeMovement();

        $response = $this->getJson('/api/movements');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_movement(): void
    {
        $client = $this->makeClient();
        $book = $this->makeBook();

        $payload = [
            'id_cliente' => $client->id_cliente,
            'id_libro' => $book->id_libro,
            'tipo_movimiento' => 'Salida',
            'fecha_movimiento' => now()->toDateTimeString(),
            'cantidad' => 2,
            'observacion' => 'Prestamo prueba',
        ];

        $response = $this->postJson('/api/movements', $payload);

        $response->assertCreated()->assertJsonFragment([
            'id_cliente' => $client->id_cliente,
            'tipo_movimiento' => 'Salida',
        ]);

        $this->assertDatabaseHas('movements', [
            'id_cliente' => $client->id_cliente,
            'id_libro' => $book->id_libro,
            'tipo_movimiento' => 'Salida',
        ]);
    }

    public function test_can_show_movement(): void
    {
        $movement = $this->makeMovement();

        $response = $this->getJson("/api/movements/{$movement->id_movimiento}");

        $response->assertOk()->assertJsonFragment([
            'id_movimiento' => $movement->id_movimiento,
        ]);
    }

    public function test_can_update_movement(): void
    {
        $movement = $this->makeMovement(['cantidad' => 3]);

        $response = $this->putJson("/api/movements/{$movement->id_movimiento}", [
            'cantidad' => 6,
        ]);

        $response->assertOk()->assertJsonFragment([
            'cantidad' => 6,
        ]);

        $this->assertDatabaseHas('movements', [
            'id_movimiento' => $movement->id_movimiento,
            'cantidad' => 6,
        ]);
    }

    public function test_can_delete_movement(): void
    {
        $movement = $this->makeMovement();

        $response = $this->deleteJson("/api/movements/{$movement->id_movimiento}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('movements', [
            'id_movimiento' => $movement->id_movimiento,
        ]);
    }
}
