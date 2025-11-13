@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-arrow-counterclockwise"></i> Devoluciones</h1>
            <p class="panel-subtitle">Controla los retornos de libros y verifica que el inventario se mantenga actualizado.</p>
        </div>
        <div class="panel-actions">
            <span class="status-pill"><i class="bi bi-arrow-repeat"></i> Totales: {{ $returns->total() }}</span>
            <a href="{{ route('web.returns.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nueva devolución</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($returns->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Reserva vinculada</th>
                            <th>Libros devueltos</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($returns as $return)
                        @php
                            $status = $return->estado ?? 'Sin estado';
                            $statusClass = match (strtolower($status)) {
                                'completada', 'devuelta', 'finalizada' => 'success',
                                'retrasada', 'pendiente' => 'warning',
                                'cancelada' => 'danger',
                                default => '',
                            };
                            $hasReturnDetails = $return->details->isNotEmpty();
                            $displayDetails = $hasReturnDetails
                                ? $return->details
                                : optional($return->reservation)->details ?? collect();
                        @endphp
                        <tr>
                            <td>
                                <span class="table-cell-title"><i class="bi bi-hash"></i>{{ $return->id_devolucion }}</span>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-person"></i> {{ $return->client?->nombre }} {{ $return->client?->apellido }}</td>
                            <td class="table-cell-note">
                                @if ($return->reservation)
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-link-45deg"></i>
                                        Reserva #{{ $return->reservation->id_reserva }}
                                    </span>
                                @else
                                    <span class="text-muted">Sin vincular</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @forelse ($displayDetails as $detail)
                                        <span class="badge {{ $hasReturnDetails ? 'bg-success-subtle text-success-emphasis' : 'bg-primary-subtle text-primary-emphasis' }} rounded-pill px-3 py-2 fw-medium">
                                            <i class="bi {{ $hasReturnDetails ? 'bi-journal-arrow-up' : 'bi-bookmark' }}"></i>
                                            {{ $detail->book?->titulo ?? 'Libro no disponible' }}
                                            <span class="text-muted ms-2">× {{ $hasReturnDetails ? $detail->cantidad_devuelta : $detail->cantidad }}</span>
                                        </span>
                                    @empty
                                        <span class="text-muted small">Sin libros asociados</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-clock-history"></i> {{ $return->fecha_devolucion?->format('d/m/Y H:i') ?? 'Sin fecha' }}</td>
                            <td>
                                <span class="table-chip {{ $statusClass }}"><i class="bi bi-arrow-repeat"></i> {{ $status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a href="{{ route('web.returns.show', $return) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.returns.edit', $return) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.returns.destroy', $return) }}" method="POST" onsubmit="return confirm('¿Eliminar devolución?');">
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
                <i class="bi bi-box-arrow-in-right"></i>
                <h3>No hay devoluciones registradas</h3>
                <p>Procesa una devolución para mantener el inventario al día.</p>
                <a href="{{ route('web.returns.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Registrar devolución</a>
            </div>
        @endif
    </div>

    @if ($returns->hasPages())
        <div class="d-flex justify-content-center">
            {{ $returns->links() }}
        </div>
    @endif
</section>
@endsection
