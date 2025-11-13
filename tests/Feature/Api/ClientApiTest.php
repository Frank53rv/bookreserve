<?php

namespace Tests\Feature\Api;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_clients(): void
    {
        $this->makeClient(['nombre' => 'Ana']);
        $this->makeClient(['nombre' => 'Luis']);

        $response = $this->getJson('/api/clients');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_client(): void
    {
        $payload = [
            'nombre' => 'Carlos',
            'apellido' => 'Perez',
            'dni' => 'DNI12345XX',
            'telefono' => '5550001',
            'correo' => 'carlos@mail.test',
            'direccion' => 'Direccion 1',
            'fecha_registro' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/clients', $payload);

        $response->assertCreated()->assertJsonFragment([
            'nombre' => 'Carlos',
            'apellido' => 'Perez',
        ]);

        $this->assertDatabaseHas('clients', [
            'nombre' => 'Carlos',
            'dni' => 'DNI12345XX',
        ]);
    }

    public function test_can_show_client(): void
    {
        $client = $this->makeClient(['nombre' => 'Mostrar']);

        $response = $this->getJson("/api/clients/{$client->id_cliente}");

        $response->assertOk()->assertJsonFragment([
            'id_cliente' => $client->id_cliente,
            'nombre' => 'Mostrar',
        ]);
    }

    public function test_can_update_client(): void
    {
        $client = $this->makeClient(['nombre' => 'Antiguo', 'correo' => 'antiguo@mail.test', 'dni' => 'DNIANTIGUO']);

        $response = $this->putJson("/api/clients/{$client->id_cliente}", [
            'nombre' => 'Nuevo',
            'correo' => 'nuevo@mail.test',
        ]);

        $response->assertOk()->assertJsonFragment([
            'nombre' => 'Nuevo',
            'correo' => 'nuevo@mail.test',
        ]);

        $this->assertDatabaseHas('clients', [
            'id_cliente' => $client->id_cliente,
            'nombre' => 'Nuevo',
            'correo' => 'nuevo@mail.test',
        ]);
    }

    public function test_can_delete_client(): void
    {
        $client = $this->makeClient();

        $response = $this->deleteJson("/api/clients/{$client->id_cliente}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('clients', [
            'id_cliente' => $client->id_cliente,
        ]);
    }
}
