<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Seed the clients table.
     */
    public function run(): void
    {
        $clients = [
            ['nombre' => 'Ana', 'apellido' => 'Gomez', 'dni' => 'CLI1001', 'correo' => 'ana.gomez@example.com', 'telefono' => '555-0101', 'direccion' => 'Calle 10 #123'],
            ['nombre' => 'Luis', 'apellido' => 'Martinez', 'dni' => 'CLI1002', 'correo' => 'luis.martinez@example.com', 'telefono' => '555-0102', 'direccion' => 'Avenida Central 45'],
            ['nombre' => 'Maria', 'apellido' => 'Lopez', 'dni' => 'CLI1003', 'correo' => 'maria.lopez@example.com', 'telefono' => '555-0103', 'direccion' => 'Calle Norte 78'],
            ['nombre' => 'Jorge', 'apellido' => 'Castro', 'dni' => 'CLI1004', 'correo' => 'jorge.castro@example.com', 'telefono' => '555-0104', 'direccion' => 'Boulevard Sur 90'],
            ['nombre' => 'Elena', 'apellido' => 'Ramos', 'dni' => 'CLI1005', 'correo' => 'elena.ramos@example.com', 'telefono' => '555-0105', 'direccion' => 'Calle 5 #56'],
            ['nombre' => 'David', 'apellido' => 'Ortiz', 'dni' => 'CLI1006', 'correo' => 'david.ortiz@example.com', 'telefono' => '555-0106', 'direccion' => 'Avenida del Sol 12'],
            ['nombre' => 'Sofia', 'apellido' => 'Vega', 'dni' => 'CLI1007', 'correo' => 'sofia.vega@example.com', 'telefono' => '555-0107', 'direccion' => 'Parque Central 8'],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['dni' => $client['dni']],
                [
                    'nombre' => $client['nombre'],
                    'apellido' => $client['apellido'],
                    'correo' => $client['correo'],
                    'telefono' => $client['telefono'],
                    'direccion' => $client['direccion'],
                    'fecha_registro' => Carbon::now()->subDays(random_int(10, 120)),
                ]
            );
        }
    }
}
