@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Reservas</h1>
    <a href="{{ route('web.reservations.create') }}" class="btn btn-primary">Nueva reserva</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->id_reserva }}</td>
                        <td>{{ $reservation->client?->nombre }} {{ $reservation->client?->apellido }}</td>
                        <td>{{ $reservation->fecha_reserva?->format('d/m/Y H:i') }}</td>
                        <td>{{ $reservation->estado }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.reservations.show', $reservation) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <a href="{{ route('web.reservations.edit', $reservation) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar reserva?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No hay reservas registradas.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($reservations->hasPages())
        <div class="card-footer">
            {{ $reservations->links() }}
        </div>
    @endif
</div>
@endsection
