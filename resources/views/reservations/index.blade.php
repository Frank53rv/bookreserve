@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-calendar2-check"></i> Reservas</h1>
            <p class="panel-subtitle">Supervisa las reservas activas y gestiona sus actualizaciones en un vistazo.</p>
        </div>
        <div class="panel-actions">
            <span class="status-pill"><i class="bi bi-shield-check"></i> Totales: {{ $reservations->total() }}</span>
            <a href="{{ route('web.reservations.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nueva reserva</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($reservations->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Libros reservados</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($reservations as $reservation)
                        @php
                            $status = $reservation->estado ?? 'Sin estado';
                            $statusClass = match (strtolower($status)) {
                                'completada', 'devuelta', 'finalizada' => 'success',
                                'cancelada', 'anulada' => 'danger',
                                default => '',
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="table-cell-title"><i class="bi bi-hash"></i>{{ $reservation->id_reserva }}</span>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-person"></i> {{ $reservation->client?->nombre }} {{ $reservation->client?->apellido }}</td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @forelse ($reservation->details as $detail)
                                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-3 py-2 fw-medium">
                                            <i class="bi bi-bookmark"></i>
                                            {{ $detail->book?->titulo }}
                                            <span class="text-muted ms-2">× {{ $detail->cantidad }}</span>
                                        </span>
                                    @empty
                                        <span class="text-muted small">Sin libros asociados</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-clock-history"></i> {{ $reservation->fecha_reserva?->format('d/m/Y H:i') ?? 'Sin fecha' }}</td>
                            <td>
                                <span class="table-chip {{ $statusClass }}"><i class="bi bi-circle-half"></i> {{ $status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <form action="{{ route('web.reservations.status', $reservation) }}" method="POST" class="d-flex flex-wrap align-items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="estado" class="form-select form-select-sm" aria-label="Actualizar estado de la reserva">
                                            @foreach (App\Models\ReservationHeader::STATES as $estado)
                                                <option value="{{ $estado }}" @selected($reservation->estado === $estado)>{{ $estado }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-outline-soft btn-sm">
                                            <i class="bi bi-arrow-repeat"></i> Actualizar
                                        </button>
                                    </form>
                                    <a href="{{ route('web.reservations.show', $reservation) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.reservations.edit', $reservation) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('¿Eliminar reserva?');">
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
                <i class="bi bi-calendar-x"></i>
                <h3>No hay reservas registradas</h3>
                <p>Crea una nueva reserva y comienza a gestionar los préstamos de tus libros.</p>
                <a href="{{ route('web.reservations.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Registrar reserva</a>
            </div>
        @endif
    </div>

    @if ($reservations->hasPages())
        <div class="d-flex justify-content-center">
            {{ $reservations->links() }}
        </div>
    @endif
</section>
@endsection
