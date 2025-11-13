@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Libros</h1>
    <a href="{{ route('web.books.create') }}" class="btn btn-primary">Nuevo libro</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Autor</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>{{ $book->titulo }}</td>
                        <td>{{ $book->category?->nombre }}</td>
                        <td>{{ $book->autor }}</td>
                        <td>{{ $book->stock_actual }}</td>
                        <td>{{ $book->estado }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.books.show', $book) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <a href="{{ route('web.books.edit', $book) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.books.destroy', $book) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar libro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No hay libros registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($books->hasPages())
        <div class="card-footer">
            {{ $books->links() }}
        </div>
    @endif
</div>
@endsection
