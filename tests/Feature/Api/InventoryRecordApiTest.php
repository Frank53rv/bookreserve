<?php

namespace Tests\Feature\Api;

use App\Models\InventoryRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Concerns\CreatesLibraryEntities;
use Tests\TestCase;

class InventoryRecordApiTest extends TestCase
{
    use RefreshDatabase;
    use CreatesLibraryEntities;

    public function test_can_list_inventory_records(): void
    {
        $this->makeInventoryRecord();
        $this->makeInventoryRecord();

        $response = $this->getJson('/api/inventory-records');

        $response->assertOk()->assertJsonCount(2);
    }

    public function test_can_create_inventory_record(): void
    {
        $book = $this->makeBook();

        $payload = [
            'id_libro' => $book->id_libro,
            'fecha_ingreso' => now()->toDateTimeString(),
            'cantidad_ingresada' => 8,
            'proveedor' => 'Proveedor Uno',
            'observacion' => 'Nota de prueba',
        ];

        $response = $this->postJson('/api/inventory-records', $payload);

        $response->assertCreated()->assertJsonFragment([
            'id_libro' => $book->id_libro,
            'cantidad_ingresada' => 8,
        ]);

        $this->assertDatabaseHas('inventory_records', [
            'id_libro' => $book->id_libro,
            'cantidad_ingresada' => 8,
        ]);
    }

    public function test_can_show_inventory_record(): void
    {
        $record = $this->makeInventoryRecord();

        $response = $this->getJson("/api/inventory-records/{$record->id_inventario}");

        $response->assertOk()->assertJsonFragment([
            'id_inventario' => $record->id_inventario,
        ]);
    }

    public function test_can_update_inventory_record(): void
    {
        $record = $this->makeInventoryRecord(['cantidad_ingresada' => 3]);

        $response = $this->putJson("/api/inventory-records/{$record->id_inventario}", [
            'cantidad_ingresada' => 12,
        ]);

        $response->assertOk()->assertJsonFragment([
            'cantidad_ingresada' => 12,
        ]);

        $this->assertDatabaseHas('inventory_records', [
            'id_inventario' => $record->id_inventario,
            'cantidad_ingresada' => 12,
        ]);
    }

    public function test_can_delete_inventory_record(): void
    {
        $record = $this->makeInventoryRecord();

        $response = $this->deleteJson("/api/inventory-records/{$record->id_inventario}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('inventory_records', [
            'id_inventario' => $record->id_inventario,
        ]);
    }
}
