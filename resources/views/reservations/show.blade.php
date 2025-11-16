@extends('layouts.app')

@section('content')
@php
    $reservedTotal = $reservation->details->sum('cantidad');
    $returnedTotal = $reservation->details->sum(fn($detail) => min($detail->cantidad, $detail->returnedQuantity()));
    $pendingTotal = max($reservedTotal - $returnedTotal, 0);
    $progress = $reservedTotal > 0 ? round(($returnedTotal / $reservedTotal) * 100) : 0;
    $estimated = $reservation->fecha_estimada_devolucion;
    $isLate = $estimated && $estimated->isPast() && $reservation->estado !== 'Completado';
@endphp

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h1 class="h3 mb-0">Reserva #{{ $reservation->id_reserva }}</h1>
    <div class="d-flex flex-wrap gap-2">
        <form action="{{ route('web.reservations.status', $reservation) }}" method="POST" class="d-flex gap-2 align-items-center">
            @csrf
            @method('PATCH')
            <select name="estado" class="form-select form-select-sm">
                @foreach (App\Models\ReservationHeader::STATES as $estado)
                    <option value="{{ $estado }}" @selected($reservation->estado === $estado)>{{ $estado }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-repeat"></i>
                Actualizar estado
            </button>
        </form>
        <a href="{{ route('web.reservations.edit', $reservation) }}" class="btn btn-primary">Editar</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-muted">Cliente</h2>
                <p class="fw-semibold mb-1">{{ $reservation->client?->nombre }} {{ $reservation->client?->apellido }}</p>
                <p class="text-muted mb-0"><i class="bi bi-clock-history"></i> {{ $reservation->fecha_reserva?->format('d/m/Y H:i') ?? 'Sin fecha' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-muted">Seguimiento</h2>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar @if($progress === 100) bg-success @elseif($progress >= 50) bg-warning @else bg-danger @endif" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mt-2 mb-0">
                    <strong>{{ $returnedTotal }}</strong> devueltos de {{ $reservedTotal }} ·
                    <span class="text-danger">{{ $pendingTotal }} pendiente{{ $pendingTotal === 1 ? '' : 's' }}</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase text-muted">Fecha estimada</h2>
                @if ($estimated)
                    <p class="fw-semibold mb-1">
                        <i class="bi bi-calendar-event"></i> {{ $estimated->format('d/m/Y H:i') }}
                    </p>
                    @if ($isLate)
                        <span class="badge bg-danger-subtle text-danger-emphasis">Vencida</span>
                    @else
                        <span class="badge bg-success-subtle text-success-emphasis">En plazo</span>
                    @endif
                @else
                    <p class="text-muted mb-0">Sin fecha estimada</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th class="text-center">Reservados</th>
                        <th class="text-center">Devueltos</th>
                        <th class="text-center">Pendientes</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($reservation->details as $detail)
                    @php
                        $remaining = $detail->remainingQuantity();
                        $returned = $detail->cantidad - $remaining;
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $detail->book?->titulo ?? 'Libro sin título' }}</div>
                            <small class="text-muted">Última actualización: {{ $detail->updated_at?->format('d/m/Y H:i') ?? 'Sin registro' }}</small>
                        </td>
                        <td class="text-center">{{ $detail->cantidad }}</td>
                        <td class="text-center text-success">{{ $returned }}</td>
                        <td class="text-center text-danger">
                            {{ $remaining }}
                            @if ($remaining > 0)
                                <span class="badge bg-warning-subtle text-warning-emphasis ms-1">Pendiente</span>
                            @else
                                <span class="badge bg-success-subtle text-success-emphasis ms-1">Completo</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No hay detalles registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('web.reservations.index') }}" class="btn btn-outline-secondary">Volver al listado</a>
    <form action="{{ route('web.reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('¿Eliminar reserva? Esta acción devolverá el stock de los libros pendientes.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Eliminar reserva</button>
    </form>
</div>
@endsection
