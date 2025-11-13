@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">{{ $client->nombre }} {{ $client->apellido }}</h1>
                <dl class="row mb-0">
                    <dt class="col-sm-4">DNI</dt>
                    <dd class="col-sm-8">{{ $client->dni }}</dd>
                    <dt class="col-sm-4">Correo</dt>
                    <dd class="col-sm-8">{{ $client->correo }}</dd>
                    <dt class="col-sm-4">Teléfono</dt>
                    <dd class="col-sm-8">{{ $client->telefono ?? 'Sin registrar' }}</dd>
                    <dt class="col-sm-4">Dirección</dt>
                    <dd class="col-sm-8">{{ $client->direccion ?? 'Sin registrar' }}</dd>
                    <dt class="col-sm-4">Fecha de registro</dt>
                    <dd class="col-sm-8">{{ $client->fecha_registro?->format('d/m/Y') }}</dd>
                </dl>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('web.clients.index') }}" class="btn btn-outline-secondary">Regresar</a>
                <div class="d-flex gap-2">
                    <a href="{{ route('web.clients.edit', $client) }}" class="btn btn-primary">Editar</a>
                    <form action="{{ route('web.clients.destroy', $client) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar cliente?')">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
