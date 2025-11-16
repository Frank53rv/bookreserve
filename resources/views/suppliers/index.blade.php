@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-people"></i> Proveedores</h1>
            <p class="panel-subtitle">Mantén el registro de proveedores para asignarlos a los lotes de ingreso.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.suppliers.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nuevo proveedor</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($suppliers->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td class="table-cell-title"><i class="bi bi-building"></i>{{ $supplier->nombre_comercial }}</td>
                            <td class="table-cell-note">{{ $supplier->contacto ?? '—' }}</td>
                            <td class="table-cell-note">{{ $supplier->telefono ?? '—' }}</td>
                            <td class="table-cell-note">{{ $supplier->correo ?? '—' }}</td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a href="{{ route('web.suppliers.show', $supplier) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.suppliers.edit', $supplier) }}" class="btn btn-outline-soft"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('¿Eliminar proveedor?');">
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
                <i class="bi bi-building"></i>
                <h3>No hay proveedores registrados</h3>
                <p>Agrega tus socios comerciales para poder registrar lotes de compra.</p>
            </div>
        @endif
    </div>

    @if ($suppliers->hasPages())
        <div class="d-flex justify-content-center">
            {{ $suppliers->links() }}
        </div>
    @endif
</section>
@endsection
