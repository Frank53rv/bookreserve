@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-journal"></i> Editoriales</h1>
            <p class="panel-subtitle">Administra las editoriales disponibles para asociarlas a los libros del catálogo.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.editorials.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nueva editorial</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($editorials->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>País</th>
                            <th>Contacto</th>
                            <th>Sitio web</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($editorials as $editorial)
                        <tr>
                            <td class="table-cell-title">
                                <i class="bi bi-journal-bookmark"></i>
                                {{ $editorial->nombre }}
                            </td>
                            <td class="table-cell-note">{{ $editorial->pais ?? '—' }}</td>
                            <td class="table-cell-note">{{ $editorial->contacto ?? '—' }}</td>
                            <td class="table-cell-note">
                                @if ($editorial->sitio_web)
                                    <a href="{{ $editorial->sitio_web }}" target="_blank" rel="noopener">{{ $editorial->sitio_web }}</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a href="{{ route('web.editorials.edit', $editorial) }}" class="btn btn-outline-soft"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.editorials.destroy', $editorial) }}" method="POST" onsubmit="return confirm('¿Eliminar editorial?');">
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
                <i class="bi bi-journal"></i>
                <h3>No hay editoriales registradas</h3>
                <p>Agrega tu primera editorial para mejorar la clasificación de los libros.</p>
            </div>
        @endif
    </div>

    @if ($editorials->hasPages())
        <div class="d-flex justify-content-center">
            {{ $editorials->links() }}
        </div>
    @endif
</section>
@endsection
