<?php

namespace Tests\Feature\Api;

use App\Models\ReservationHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class ReservationHeaderApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_reservations(): void
    {
        $this->makeReservation();
        $this->makeReservation();

        $response = $this->getJson('/api/reservations');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_reservation(): void
    {
        $client = $this->makeClient();
        $payload = [
            'id_cliente' => $client->id_cliente,
            'fecha_reserva' => now()->toDateTimeString(),
            'estado' => 'Pendiente',
        ];

        $response = $this->postJson('/api/reservations', $payload);

        $response->assertCreated()->assertJsonFragment([
            'id_cliente' => $client->id_cliente,
            'estado' => 'Pendiente',
        ]);

        $this->assertDatabaseHas('reservation_headers', [
            'id_cliente' => $client->id_cliente,
            'estado' => 'Pendiente',
        ]);
    }

    public function test_can_show_reservation(): void
    {
        $reservation = $this->makeReservation();

        $response = $this->getJson("/api/reservations/{$reservation->id_reserva}");

        $response->assertOk()->assertJsonFragment([
            'id_reserva' => $reservation->id_reserva,
        ]);
    }

    public function test_can_update_reservation(): void
    {
        $reservation = $this->makeReservation(['estado' => 'Pendiente']);

        $response = $this->putJson("/api/reservations/{$reservation->id_reserva}", [
            'estado' => 'Retirado',
        ]);

        $response->assertOk()->assertJsonFragment([
            'estado' => 'Retirado',
        ]);

        $this->assertDatabaseHas('reservation_headers', [
            'id_reserva' => $reservation->id_reserva,
            'estado' => 'Retirado',
        ]);
    }

    public function test_can_delete_reservation(): void
    {
        $reservation = $this->makeReservation();

        $response = $this->deleteJson("/api/reservations/{$reservation->id_reserva}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('reservation_headers', [
            'id_reserva' => $reservation->id_reserva,
        ]);
    }
}
