<?php

namespace Tests\Feature\Api;

use App\Models\ReservationDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class ReservationDetailApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_reservation_details(): void
    {
        $this->makeReservationDetail();
        $this->makeReservationDetail();

        $response = $this->getJson('/api/reservation-details');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_reservation_detail(): void
    {
        $reservation = $this->makeReservation();
        $book = $this->makeBook();

        $payload = [
            'id_reserva' => $reservation->id_reserva,
            'id_libro' => $book->id_libro,
            'cantidad' => 2,
        ];

        $response = $this->postJson('/api/reservation-details', $payload);

        $response->assertCreated()->assertJsonFragment([
            'id_reserva' => $reservation->id_reserva,
            'id_libro' => $book->id_libro,
            'cantidad' => 2,
        ]);

        $this->assertDatabaseHas('reservation_details', [
            'id_reserva' => $reservation->id_reserva,
            'id_libro' => $book->id_libro,
            'cantidad' => 2,
        ]);
    }

    public function test_can_show_reservation_detail(): void
    {
        $detail = $this->makeReservationDetail();

        $response = $this->getJson("/api/reservation-details/{$detail->id_detalle_reserva}");

        $response->assertOk()->assertJsonFragment([
            'id_detalle_reserva' => $detail->id_detalle_reserva,
        ]);
    }

    public function test_can_update_reservation_detail(): void
    {
        $detail = $this->makeReservationDetail(['cantidad' => 1]);

        $response = $this->putJson("/api/reservation-details/{$detail->id_detalle_reserva}", [
            'cantidad' => 4,
        ]);

        $response->assertOk()->assertJsonFragment([
            'cantidad' => 4,
        ]);

        $this->assertDatabaseHas('reservation_details', [
            'id_detalle_reserva' => $detail->id_detalle_reserva,
            'cantidad' => 4,
        ]);
    }

    public function test_can_delete_reservation_detail(): void
    {
        $detail = $this->makeReservationDetail();

        $response = $this->deleteJson("/api/reservation-details/{$detail->id_detalle_reserva}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('reservation_details', [
            'id_detalle_reserva' => $detail->id_detalle_reserva,
        ]);
    }
}
