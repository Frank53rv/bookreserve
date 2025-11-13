<?php

namespace Tests\Feature\Api;

use App\Models\ReturnDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class ReturnDetailApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_return_details(): void
    {
        $this->makeReturnDetail();
        $this->makeReturnDetail();

        $response = $this->getJson('/api/return-details');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_return_detail(): void
    {
        $return = $this->makeReturn();
        $book = $this->makeBook();

        $payload = [
            'id_devolucion' => $return->id_devolucion,
            'id_libro' => $book->id_libro,
            'cantidad_devuelta' => 2,
        ];

        $response = $this->postJson('/api/return-details', $payload);

        $response->assertCreated()->assertJsonFragment([
            'id_devolucion' => $return->id_devolucion,
            'id_libro' => $book->id_libro,
            'cantidad_devuelta' => 2,
        ]);

        $this->assertDatabaseHas('return_details', [
            'id_devolucion' => $return->id_devolucion,
            'id_libro' => $book->id_libro,
            'cantidad_devuelta' => 2,
        ]);
    }

    public function test_can_show_return_detail(): void
    {
        $detail = $this->makeReturnDetail();

        $response = $this->getJson("/api/return-details/{$detail->id_detalle_devolucion}");

        $response->assertOk()->assertJsonFragment([
            'id_detalle_devolucion' => $detail->id_detalle_devolucion,
        ]);
    }

    public function test_can_update_return_detail(): void
    {
        $detail = $this->makeReturnDetail(['cantidad_devuelta' => 1]);

        $response = $this->putJson("/api/return-details/{$detail->id_detalle_devolucion}", [
            'cantidad_devuelta' => 5,
        ]);

        $response->assertOk()->assertJsonFragment([
            'cantidad_devuelta' => 5,
        ]);

        $this->assertDatabaseHas('return_details', [
            'id_detalle_devolucion' => $detail->id_detalle_devolucion,
            'cantidad_devuelta' => 5,
        ]);
    }

    public function test_can_delete_return_detail(): void
    {
        $detail = $this->makeReturnDetail();

        $response = $this->deleteJson("/api/return-details/{$detail->id_detalle_devolucion}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('return_details', [
            'id_detalle_devolucion' => $detail->id_detalle_devolucion,
        ]);
    }
}
