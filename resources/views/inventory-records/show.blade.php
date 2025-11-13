@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Ingreso #{{ $inventoryRecord->id_inventario }}</h1>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Libro</dt>
                    <dd class="col-sm-8">{{ $inventoryRecord->book?->titulo }}</dd>
                    <dt class="col-sm-4">Fecha</dt>
                    <dd class="col-sm-8">{{ $inventoryRecord->fecha_ingreso?->format('d/m/Y H:i') }}</dd>
                    <dt class="col-sm-4">Cantidad</dt>
                    <dd class="col-sm-8">{{ $inventoryRecord->cantidad_ingresada }}</dd>
                    <dt class="col-sm-4">Proveedor</dt>
                    <dd class="col-sm-8">{{ $inventoryRecord->proveedor ?? 'Sin registrar' }}</dd>
                    <dt class="col-sm-4">Observación</dt>
                    <dd class="col-sm-8">{{ $inventoryRecord->observacion ?? 'Sin observaciones' }}</dd>
                </dl>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('web.inventory-records.index') }}" class="btn btn-outline-secondary">Regresar</a>
                <div class="d-flex gap-2">
                    <a href="{{ route('web.inventory-records.edit', $inventoryRecord) }}" class="btn btn-primary">Editar</a>
                    <form action="{{ route('web.inventory-records.destroy', $inventoryRecord) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar ingreso?')">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
