@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Categorías</h1>
    <a href="{{ route('web.categories.create') }}" class="btn btn-primary">Nueva categoría</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->nombre }}</td>
                        <td>{{ $category->descripcion }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.categories.show', $category) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <a href="{{ route('web.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar categoría?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">No hay categorías registradas.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($categories->hasPages())
        <div class="card-footer">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
