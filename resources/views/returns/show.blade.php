@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Devolución #{{ $return->id_devolucion }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('web.return-details.create', ['return' => $return->id_devolucion]) }}" class="btn btn-outline-primary">Agregar detalle</a>
        <a href="{{ route('web.returns.edit', $return) }}" class="btn btn-primary">Editar</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Cliente</dt>
                    <dd class="col-sm-8">{{ $return->client?->nombre }} {{ $return->client?->apellido }}</dd>
                    <dt class="col-sm-4">Fecha</dt>
                    <dd class="col-sm-8">{{ $return->fecha_devolucion?->format('d/m/Y H:i') }}</dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">{{ $return->estado }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Cantidad devuelta</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($return->details as $detail)
                    <tr>
                        <td>{{ $detail->book?->titulo }}</td>
                        <td>{{ $detail->cantidad_devuelta }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.return-details.edit', $detail) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.return-details.destroy', $detail) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar detalle?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">No hay detalles registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    <form action="{{ route('web.returns.destroy', $return) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar devolución?')">Eliminar devolución</button>
    </form>
    <a href="{{ route('web.returns.index') }}" class="btn btn-outline-secondary ms-2">Volver al listado</a>
</div>
@endsection
