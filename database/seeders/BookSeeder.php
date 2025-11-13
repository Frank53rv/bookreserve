<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Seed the books table.
     */
    public function run(): void
    {
        $categoryIds = Category::query()->pluck('id_categoria', 'nombre');

        $books = [
            ['titulo' => 'El Ocaso de Orion', 'autor' => 'Laura Perez', 'editorial' => 'Editorial Solaris', 'anio_publicacion' => 2021, 'isbn' => '9780000000001', 'category' => 'Ficcion Contemporanea', 'stock_actual' => 12, 'estado' => 'Disponible'],
            ['titulo' => 'Circuitos del Futuro', 'autor' => 'Miguel Torres', 'editorial' => 'TechPress', 'anio_publicacion' => 2022, 'isbn' => '9780000000002', 'category' => 'Ciencia y Tecnologia', 'stock_actual' => 9, 'estado' => 'Disponible'],
            ['titulo' => 'Cronicas de Imperios', 'autor' => 'Silvia Ramos', 'editorial' => 'Historia Viva', 'anio_publicacion' => 2019, 'isbn' => '9780000000003', 'category' => 'Historia', 'stock_actual' => 6, 'estado' => 'Disponible'],
            ['titulo' => 'Habitos para Crecer', 'autor' => 'Carlos Medina', 'editorial' => 'Ser Integral', 'anio_publicacion' => 2020, 'isbn' => '9780000000004', 'category' => 'Desarrollo Personal', 'stock_actual' => 15, 'estado' => 'Disponible'],
            ['titulo' => 'Exploradores del Manana', 'autor' => 'Paula Reyes', 'editorial' => 'Luz Verde', 'anio_publicacion' => 2023, 'isbn' => '9780000000005', 'category' => 'Infantil y Juvenil', 'stock_actual' => 18, 'estado' => 'Disponible'],
            ['titulo' => 'Fundamentos de Matematica', 'autor' => 'Andres Salas', 'editorial' => 'EduBooks', 'anio_publicacion' => 2018, 'isbn' => '9780000000006', 'category' => 'Educacion', 'stock_actual' => 10, 'estado' => 'Disponible'],
            ['titulo' => 'El Arte de Crear', 'autor' => 'Veronica Lopez', 'editorial' => 'Studio Press', 'anio_publicacion' => 2021, 'isbn' => '9780000000007', 'category' => 'Arte y Diseno', 'stock_actual' => 7, 'estado' => 'Disponible'],
            ['titulo' => 'Susurros de la Ciudad', 'autor' => 'Diego Herrera', 'editorial' => 'Urbano', 'anio_publicacion' => 2017, 'isbn' => '9780000000008', 'category' => 'Ficcion Contemporanea', 'stock_actual' => 5, 'estado' => 'Disponible'],
            ['titulo' => 'Inteligencia Artificial Practica', 'autor' => 'Natalia Cruz', 'editorial' => 'TechPress', 'anio_publicacion' => 2024, 'isbn' => '9780000000009', 'category' => 'Ciencia y Tecnologia', 'stock_actual' => 14, 'estado' => 'Disponible'],
            ['titulo' => 'Rutas de la Libertad', 'autor' => 'Ernesto Luna', 'editorial' => 'Historia Viva', 'anio_publicacion' => 2016, 'isbn' => '9780000000010', 'category' => 'Historia', 'stock_actual' => 4, 'estado' => 'No disponible'],
            ['titulo' => 'Productividad Sostenible', 'autor' => 'Laura Jimenez', 'editorial' => 'Ser Integral', 'anio_publicacion' => 2022, 'isbn' => '9780000000011', 'category' => 'Desarrollo Personal', 'stock_actual' => 11, 'estado' => 'Disponible'],
            ['titulo' => 'Guia de Aventuras Juveniles', 'autor' => 'Ricardo Solis', 'editorial' => 'Luz Verde', 'anio_publicacion' => 2020, 'isbn' => '9780000000012', 'category' => 'Infantil y Juvenil', 'stock_actual' => 9, 'estado' => 'Disponible'],
            ['titulo' => 'Didactica Moderna', 'autor' => 'Monica Diaz', 'editorial' => 'EduBooks', 'anio_publicacion' => 2015, 'isbn' => '9780000000013', 'category' => 'Educacion', 'stock_actual' => 3, 'estado' => 'Disponible'],
            ['titulo' => 'Diseno Conceptual', 'autor' => 'Camila Soto', 'editorial' => 'Studio Press', 'anio_publicacion' => 2019, 'isbn' => '9780000000014', 'category' => 'Arte y Diseno', 'stock_actual' => 8, 'estado' => 'Disponible'],
            ['titulo' => 'Mareas Secretas', 'autor' => 'Hector Alvarez', 'editorial' => 'Editorial Solaris', 'anio_publicacion' => 2018, 'isbn' => '9780000000015', 'category' => 'Ficcion Contemporanea', 'stock_actual' => 6, 'estado' => 'No disponible'],
            ['titulo' => 'Bioetica Contemporanea', 'autor' => 'Patricia Rios', 'editorial' => 'TechPress', 'anio_publicacion' => 2021, 'isbn' => '9780000000016', 'category' => 'Ciencia y Tecnologia', 'stock_actual' => 13, 'estado' => 'Disponible'],
            ['titulo' => 'Historias del Sur', 'autor' => 'Mariano Castro', 'editorial' => 'Historia Viva', 'anio_publicacion' => 2014, 'isbn' => '9780000000017', 'category' => 'Historia', 'stock_actual' => 5, 'estado' => 'Disponible'],
            ['titulo' => 'Mindfulness Diario', 'autor' => 'Andrea Leon', 'editorial' => 'Ser Integral', 'anio_publicacion' => 2023, 'isbn' => '9780000000018', 'category' => 'Desarrollo Personal', 'stock_actual' => 16, 'estado' => 'Disponible'],
            ['titulo' => 'Laboratorio de Ideas', 'autor' => 'Fernando Ruiz', 'editorial' => 'TechPress', 'anio_publicacion' => 2019, 'isbn' => '9780000000019', 'category' => 'Ciencia y Tecnologia', 'stock_actual' => 7, 'estado' => 'Disponible'],
            ['titulo' => 'Color y Forma', 'autor' => 'Isabel Duarte', 'editorial' => 'Studio Press', 'anio_publicacion' => 2020, 'isbn' => '9780000000020', 'category' => 'Arte y Diseno', 'stock_actual' => 12, 'estado' => 'Disponible'],
        ];

        foreach ($books as $book) {
            if (! isset($categoryIds[$book['category']])) {
                continue;
            }

            Book::updateOrCreate(
                ['isbn' => $book['isbn']],
                [
                    'titulo' => $book['titulo'],
                    'autor' => $book['autor'],
                    'editorial' => $book['editorial'],
                    'anio_publicacion' => $book['anio_publicacion'],
                    'id_categoria' => $categoryIds[$book['category']],
                    'stock_actual' => $book['stock_actual'],
                    'estado' => $book['estado'],
                ]
            );
        }
    }
}
