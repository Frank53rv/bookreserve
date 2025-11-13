<?php

namespace Tests\Feature\Api;

use App\Models\ReturnHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class ReturnHeaderApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_returns(): void
    {
        $this->makeReturn();
        $this->makeReturn();

        $response = $this->getJson('/api/returns');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_return(): void
    {
        $client = $this->makeClient();

        $payload = [
            'id_cliente' => $client->id_cliente,
            'fecha_devolucion' => now()->toDateTimeString(),
            'estado' => 'Parcial',
        ];

        $response = $this->postJson('/api/returns', $payload);

        $response->assertCreated()->assertJsonFragment([
            'id_cliente' => $client->id_cliente,
            'estado' => 'Parcial',
        ]);

        $this->assertDatabaseHas('return_headers', [
            'id_cliente' => $client->id_cliente,
            'estado' => 'Parcial',
        ]);
    }

    public function test_can_show_return(): void
    {
        $return = $this->makeReturn();

        $response = $this->getJson("/api/returns/{$return->id_devolucion}");

        $response->assertOk()->assertJsonFragment([
            'id_devolucion' => $return->id_devolucion,
        ]);
    }

    public function test_can_update_return(): void
    {
        $return = $this->makeReturn(['estado' => 'Completa']);

        $response = $this->putJson("/api/returns/{$return->id_devolucion}", [
            'estado' => 'Parcial',
        ]);

        $response->assertOk()->assertJsonFragment([
            'estado' => 'Parcial',
        ]);

        $this->assertDatabaseHas('return_headers', [
            'id_devolucion' => $return->id_devolucion,
            'estado' => 'Parcial',
        ]);
    }

    public function test_can_delete_return(): void
    {
        $return = $this->makeReturn();

        $response = $this->deleteJson("/api/returns/{$return->id_devolucion}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('return_headers', [
            'id_devolucion' => $return->id_devolucion,
        ]);
    }
}
