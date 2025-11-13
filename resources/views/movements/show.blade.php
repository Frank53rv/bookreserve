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
                </dl>
            </div>
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
