@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-collection"></i> Categorías</h1>
            <p class="panel-subtitle">Organiza los libros por colecciones y facilita las búsquedas del catálogo.</p>
        </div>
        <div class="panel-actions">
            <span class="status-pill"><i class="bi bi-grid"></i> Total: {{ $categories->total() }}</span>
            <a href="{{ route('web.categories.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nueva categoría</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($categories->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                <span class="table-cell-title"><i class="bi bi-tag"></i>{{ $category->nombre }}</span>
                            </td>
                            <td class="table-cell-note">{{ $category->descripcion ?? 'Sin descripción registrada' }}</td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a href="{{ route('web.categories.show', $category) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.categories.edit', $category) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Eliminar categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-soft btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="panel-empty">
                <i class="bi bi-inboxes"></i>
                <h3>No hay categorías registradas</h3>
                <p>Crea tu primera categoría para empezar a organizar el catálogo.</p>
                <a href="{{ route('web.categories.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Registrar categoría</a>
            </div>
        @endif
    </div>

    @if ($categories->hasPages())
        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    @endif
</section>
@endsection
