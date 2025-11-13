<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Client;
use App\Models\Movement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MovementSeeder extends Seeder
{
    /**
     * Seed the movements table with sample activity.
     */
    public function run(): void
    {
        $clientIds = Client::query()->pluck('id_cliente', 'correo');
        $bookIds = Book::query()->pluck('id_libro', 'isbn');
        $now = Carbon::now();

        $movements = [
            ['client' => 'ana.gomez@example.com', 'isbn' => '9780000000005', 'tipo' => 'Salida', 'offset_days' => 2, 'hour' => 10, 'minute' => 15, 'cantidad' => 1, 'observacion' => 'Prestamo estandar'],
            ['client' => 'luis.martinez@example.com', 'isbn' => '9780000000002', 'tipo' => 'Salida', 'offset_days' => 5, 'hour' => 14, 'minute' => 30, 'cantidad' => 1, 'observacion' => 'Proyecto de investigacion'],
            ['client' => 'maria.lopez@example.com', 'isbn' => '9780000000018', 'tipo' => 'Entrada', 'offset_days' => 1, 'hour' => 9, 'minute' => 45, 'cantidad' => 2, 'observacion' => 'Reposicion de ejemplares'],
            ['client' => 'jorge.castro@example.com', 'isbn' => '9780000000010', 'tipo' => 'Salida', 'offset_days' => 8, 'hour' => 16, 'minute' => 5, 'cantidad' => 1, 'observacion' => 'Consulta historica'],
            ['client' => 'elena.ramos@example.com', 'isbn' => '9780000000013', 'tipo' => 'Entrada', 'offset_days' => 3, 'hour' => 11, 'minute' => 20, 'cantidad' => 3, 'observacion' => 'Donacion de material'],
            ['client' => 'david.ortiz@example.com', 'isbn' => '9780000000016', 'tipo' => 'Salida', 'offset_days' => 6, 'hour' => 15, 'minute' => 10, 'cantidad' => 1, 'observacion' => 'Seminario de bioetica'],
            ['client' => 'sofia.vega@example.com', 'isbn' => '9780000000008', 'tipo' => 'Salida', 'offset_days' => 4, 'hour' => 13, 'minute' => 55, 'cantidad' => 1, 'observacion' => 'Lectura recreativa'],
            ['client' => 'ana.gomez@example.com', 'isbn' => '9780000000011', 'tipo' => 'Devolucion', 'offset_days' => 1, 'hour' => 17, 'minute' => 40, 'cantidad' => 1, 'observacion' => 'Devolucion sin novedades'],
            ['client' => 'luis.martinez@example.com', 'isbn' => '9780000000012', 'tipo' => 'Entrada', 'offset_days' => 0, 'hour' => 10, 'minute' => 5, 'cantidad' => 2, 'observacion' => 'Reposicion por inventario'],
            ['client' => 'maria.lopez@example.com', 'isbn' => '9780000000009', 'tipo' => 'Salida', 'offset_days' => 7, 'hour' => 9, 'minute' => 0, 'cantidad' => 1, 'observacion' => 'Curso de IA'],
            ['client' => 'jorge.castro@example.com', 'isbn' => '9780000000006', 'tipo' => 'Entrada', 'offset_days' => 2, 'hour' => 12, 'minute' => 25, 'cantidad' => 1, 'observacion' => 'Actualizacion de stock'],
            ['client' => 'sofia.vega@example.com', 'isbn' => '9780000000020', 'tipo' => 'Salida', 'offset_days' => 3, 'hour' => 18, 'minute' => 0, 'cantidad' => 1, 'observacion' => 'Taller de arte'],
        ];

        foreach ($movements as $movement) {
            if (! isset($clientIds[$movement['client']], $bookIds[$movement['isbn']])) {
                continue;
            }

            $timestamp = $now->copy()->subDays($movement['offset_days'])->setTime($movement['hour'], $movement['minute']);

            Movement::updateOrCreate(
                [
                    'id_cliente' => $clientIds[$movement['client']],
                    'id_libro' => $bookIds[$movement['isbn']],
                    'fecha_movimiento' => $timestamp,
                ],
                [
                    'tipo_movimiento' => $movement['tipo'],
                    'cantidad' => $movement['cantidad'],
                    'observacion' => $movement['observacion'],
                ]
            );
        }
    }
}
