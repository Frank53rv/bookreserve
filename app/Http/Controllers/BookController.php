<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $books = Book::query()
            ->with('category:id_categoria,nombre')
            ->orderBy('titulo')
            ->get();

        return response()->json($books);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'autor' => ['required', 'string', 'max:100'],
            'editorial' => ['nullable', 'string', 'max:100'],
            'anio_publicacion' => ['nullable', 'integer', 'digits:4'],
            'isbn' => ['nullable', 'string', 'max:30', Rule::unique('books', 'isbn')],
            'id_categoria' => ['required', 'exists:categories,id_categoria'],
            'stock_actual' => ['nullable', 'integer', 'min:0'],
            'estado' => ['required', Rule::in(['Disponible', 'No disponible'])],
        ]);

        $data['stock_actual'] = $data['stock_actual'] ?? 0;

        $book = Book::create($data);

        return response()->json($book->load('category'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json($book->load('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $data = $request->validate([
            'titulo' => ['sometimes', 'required', 'string', 'max:150'],
            'autor' => ['sometimes', 'required', 'string', 'max:100'],
            'editorial' => ['nullable', 'string', 'max:100'],
            'anio_publicacion' => ['nullable', 'integer', 'digits:4'],
            'isbn' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('books', 'isbn')->ignore($book->getKey(), $book->getKeyName()),
            ],
            'id_categoria' => ['sometimes', 'required', 'exists:categories,id_categoria'],
            'stock_actual' => ['nullable', 'integer', 'min:0'],
            'estado' => ['sometimes', 'required', Rule::in(['Disponible', 'No disponible'])],
        ]);

        $book->update($data);

        return response()->json($book->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json(null, 204);
    }
}
