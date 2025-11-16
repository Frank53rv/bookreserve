<?php

namespace Tests\Feature\Api\Concerns;

use App\Models\Book;
use App\Models\Category;
use App\Models\Client;
use App\Models\Editorial;
use App\Models\InventoryRecord;
use App\Models\Movement;
use App\Models\ReservationDetail;
use App\Models\ReservationHeader;
use App\Models\ReturnDetail;
use App\Models\ReturnHeader;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait CreatesLibraryEntities
{
    protected function makeCategory(array $overrides = []): Category
    {
        $defaults = [
            'nombre' => 'Categoria ' . Str::random(8),
            'descripcion' => 'Descripcion ' . Str::random(12),
        ];

        return Category::create(array_merge($defaults, $overrides));
    }

    protected function makeBook(array $overrides = []): Book
    {
        $category = $overrides['category'] ?? null;
        if (! $category instanceof Category) {
            $category = $this->makeCategory();
        }

        $editorial = $overrides['editorial_model'] ?? null;
        if (! $editorial instanceof Editorial) {
            $editorial = $this->makeEditorial();
        }

        $attributes = array_merge([
            'titulo' => 'Libro ' . Str::random(10),
            'autor' => 'Autor ' . Str::random(10),
            'anio_publicacion' => 2020,
            'isbn' => Str::upper(Str::random(13)),
            'id_categoria' => $category->id_categoria,
            'id_editorial' => $editorial->id_editorial,
            'stock_actual' => 5,
            'precio_venta' => 35,
            'estado' => 'Disponible',
        ], Arr::except($overrides, ['category', 'editorial_model']));

        return Book::create($attributes);
    }

    protected function makeEditorial(array $overrides = []): Editorial
    {
        $defaults = [
            'nombre' => 'Editorial ' . Str::random(6),
            'pais' => 'Perú',
            'contacto' => 'contacto@' . Str::lower(Str::random(5)) . '.test',
        ];

        return Editorial::create(array_merge($defaults, $overrides));
    }

    protected function makeClient(array $overrides = []): Client
    {
        $defaults = [
            'nombre' => 'Nombre ' . Str::random(6),
            'apellido' => 'Apellido ' . Str::random(6),
            'dni' => Str::upper(Str::random(10)),
            'telefono' => '555' . random_int(1000, 9999),
            'correo' => Str::lower(Str::random(6)) . '@mail.test',
            'direccion' => 'Calle ' . random_int(1, 100),
            'fecha_registro' => Carbon::now()->toDateString(),
        ];

        return Client::create(array_merge($defaults, $overrides));
    }

    protected function makeReservation(array $overrides = []): ReservationHeader
    {
        $client = $overrides['client'] ?? null;
        if (! $client instanceof Client) {
            $client = $this->makeClient();
        }

        $attributes = array_merge([
            'id_cliente' => $client->id_cliente,
            'fecha_reserva' => Carbon::now(),
            'estado' => ReservationHeader::STATES[0],
        ], Arr::except($overrides, ['client']));

        return ReservationHeader::create($attributes);
    }

    protected function makeReservationDetail(array $overrides = []): ReservationDetail
    {
        $reservation = $overrides['reservation'] ?? null;
        if (! $reservation instanceof ReservationHeader) {
            $reservation = $this->makeReservation();
        }

        $book = $overrides['book'] ?? null;
        if (! $book instanceof Book) {
            $book = $this->makeBook();
        }

        $attributes = array_merge([
            'id_reserva' => $reservation->id_reserva,
            'id_libro' => $book->id_libro,
            'cantidad' => 1,
        ], Arr::except($overrides, ['reservation', 'book']));

        return ReservationDetail::create($attributes);
    }

    protected function makeReturn(array $overrides = []): ReturnHeader
    {
        $client = $overrides['client'] ?? null;
        if (! $client instanceof Client) {
            $client = $this->makeClient();
        }

        $attributes = array_merge([
            'id_cliente' => $client->id_cliente,
            'fecha_devolucion' => Carbon::now(),
            'estado' => 'Completa',
        ], Arr::except($overrides, ['client']));

        return ReturnHeader::create($attributes);
    }

    protected function makeReturnDetail(array $overrides = []): ReturnDetail
    {
        $returnHeader = $overrides['returnHeader'] ?? null;
        if (! $returnHeader instanceof ReturnHeader) {
            $returnHeader = $this->makeReturn();
        }

        $book = $overrides['book'] ?? null;
        if (! $book instanceof Book) {
            $book = $this->makeBook();
        }

        $attributes = array_merge([
            'id_devolucion' => $returnHeader->id_devolucion,
            'id_libro' => $book->id_libro,
            'cantidad_devuelta' => 1,
        ], Arr::except($overrides, ['returnHeader', 'book']));

        return ReturnDetail::create($attributes);
    }

    protected function makeInventoryRecord(array $overrides = []): InventoryRecord
    {
        $book = $overrides['book'] ?? null;
        if (! $book instanceof Book) {
            $book = $this->makeBook();
        }

        $attributes = array_merge([
            'id_libro' => $book->id_libro,
            'fecha_ingreso' => Carbon::now(),
            'cantidad_ingresada' => 10,
            'proveedor' => 'Proveedor ' . Str::random(6),
            'observacion' => 'Observacion ' . Str::random(12),
        ], Arr::except($overrides, ['book']));

        return InventoryRecord::create($attributes);
    }

    protected function makeMovement(array $overrides = []): Movement
    {
        $client = $overrides['client'] ?? null;
        if (! $client instanceof Client) {
            $client = $this->makeClient();
        }

        $book = $overrides['book'] ?? null;
        if (! $book instanceof Book) {
            $book = $this->makeBook();
        }

        $attributes = array_merge([
            'id_cliente' => $client->id_cliente,
            'id_libro' => $book->id_libro,
            'tipo_movimiento' => 'Entrada',
            'fecha_movimiento' => Carbon::now(),
            'cantidad' => 3,
            'observacion' => 'Movimiento ' . Str::random(10),
        ], Arr::except($overrides, ['client', 'book']));

        return Movement::create($attributes);
    }
}
