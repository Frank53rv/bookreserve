@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Movimiento #{{ $movement->id_movimiento }}</h1>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Cliente</dt>
                    <dd class="col-sm-8">{{ $movement->client?->nombre }} {{ $movement->client?->apellido }}</dd>
                    <dt class="col-sm-4">Libro</dt>
                    <dd class="col-sm-8">{{ $movement->book?->titulo }}</dd>
                    <dt class="col-sm-4">Tipo</dt>
                    <dd class="col-sm-8">{{ $movement->tipo_movimiento }}</dd>
                    <dt class="col-sm-4">Fecha</dt>
                    <dd class="col-sm-8">{{ $movement->fecha_movimiento?->format('d/m/Y H:i') }}</dd>
                    <dt class="col-sm-4">Cantidad</dt>
                    <dd class="col-sm-8">{{ $movement->cantidad }}</dd>
                        <dt class="col-sm-4">Observación</dt>
                    <dd class="col-sm-8">{{ $movement->observacion ?? 'Sin observaciones' }}</dd>
                    <dt class="col-sm-4">Documento asociado</dt>
                    <dd class="col-sm-8">
                        @if ($movement->reservation)
                            <a href="{{ route('web.reservations.show', $movement->reservation) }}" class="link-primary">Reserva #{{ $movement->reservation->id_reserva }}</a>
                        @elseif ($movement->returnHeader)
                            <a href="{{ route('web.returns.show', $movement->returnHeader) }}" class="link-primary">Devolución #{{ $movement->returnHeader->id_devolucion }}</a>
                        @else
                            Registro manual
                        @endif
                    </dd>
                </dl>
            </div>
            @if ($movement->logs->isNotEmpty())
                <div class="card-body border-top">
                    <h2 class="h6">Bitácora</h2>
                    <ul class="list-group list-group-flush">
                        @foreach ($movement->logs as $log)
                            <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                <div>
                                    <strong>{{ $log->descripcion }}</strong>
                                    @if (!empty($log->contexto))
                                        <pre class="mb-0 small text-muted">{{ json_encode($log->contexto, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    @endif
                                </div>
                                <span class="text-muted small mt-2 mt-md-0">
                                    <i class="bi bi-clock-history"></i>
                                    {{ $log->created_at?->format('d/m/Y H:i:s') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('web.movements.index') }}" class="btn btn-outline-secondary">Regresar</a>
                <div class="d-flex gap-2">
                    <a href="{{ route('web.movements.edit', $movement) }}" class="btn btn-primary">Editar</a>
                    <form action="{{ route('web.movements.destroy', $movement) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar movimiento?')">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
