<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories table.
     */
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Ficcion Contemporanea', 'descripcion' => 'Narrativas actuales y bestsellers.'],
            ['nombre' => 'Ciencia y Tecnologia', 'descripcion' => 'Divulgacion y avances cientificos.'],
            ['nombre' => 'Historia', 'descripcion' => 'Acontecimientos y biografias historicas.'],
            ['nombre' => 'Desarrollo Personal', 'descripcion' => 'Habitos, productividad y bienestar.'],
            ['nombre' => 'Infantil y Juvenil', 'descripcion' => 'Lecturas para ninos y adolescentes.'],
            ['nombre' => 'Educacion', 'descripcion' => 'Material academico y de formacion.'],
            ['nombre' => 'Arte y Diseno', 'descripcion' => 'Inspiracion creativa y estudios de arte.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['nombre' => $category['nombre']],
                [
                    'descripcion' => $category['descripcion'],
                ]
            );
        }
    }
}
