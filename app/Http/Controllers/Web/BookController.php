<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Editorial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::with(['category', 'editorial'])
            ->withCount('reservationDetails')
            ->orderBy('titulo')
            ->paginate(12);

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('nombre')->pluck('nombre', 'id_categoria');
        $editorials = Editorial::orderBy('nombre')->pluck('nombre', 'id_editorial');

        return view('books.create', compact('categories', 'editorials'));
    }

    public function store(Request $request): RedirectResponse
    {
        $currentYear = Carbon::now()->year + 1;

        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'autor' => ['required', 'string', 'max:100'],
            'id_editorial' => ['nullable', 'exists:editorials,id_editorial'],
            'anio_publicacion' => ['nullable', 'integer', 'between:1500,' . $currentYear],
            'isbn' => ['nullable', 'string', 'max:30'],
            'id_categoria' => ['required', 'exists:categories,id_categoria'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'in:Disponible,No disponible'],
        ]);

        Book::create($data);

        return redirect()->route('web.books.index')->with('status', 'Libro creado correctamente.');
    }

    public function show(Book $book): View
    {
        $book->load('category', 'editorial');

        return view('books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $categories = Category::orderBy('nombre')->pluck('nombre', 'id_categoria');
        $editorials = Editorial::orderBy('nombre')->pluck('nombre', 'id_editorial');

        return view('books.edit', compact('book', 'categories', 'editorials'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $currentYear = Carbon::now()->year + 1;

        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'autor' => ['required', 'string', 'max:100'],
            'id_editorial' => ['nullable', 'exists:editorials,id_editorial'],
            'anio_publicacion' => ['nullable', 'integer', 'between:1500,' . $currentYear],
            'isbn' => ['nullable', 'string', 'max:30'],
            'id_categoria' => ['required', 'exists:categories,id_categoria'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'in:Disponible,No disponible'],
        ]);

        $book->update($data);

        return redirect()->route('web.books.index')->with('status', 'Libro actualizado correctamente.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('web.books.index')->with('status', 'Libro eliminado correctamente.');
    }
}
